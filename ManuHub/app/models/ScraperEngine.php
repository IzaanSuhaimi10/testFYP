<?php
class ScraperEngine {
    
    // [Keep discoverRelatedWorks exactly as it was]
    public function discoverRelatedWorks($title) {
        $results = [];
        $cleanTitle = $this->cleanTitleForAPI($title);
        $apiUrl = "https://api.openalex.org/works?search=" . urlencode($cleanTitle) . "&per-page=15";
        $json = $this->fetchJSON($apiUrl);
        if ($json) {
            $data = json_decode($json, true);
            if (!empty($data['results'])) {
                foreach ($data['results'] as $work) {
                    if (count($results) >= 6) break;
                    $url = $work['doi'] ?? $work['landing_page_url'] ?? ($work['id'] ?? null);
                    if ($url && $this->isLinkAlive($url)) {
                        $results[] = [
                            'title' => $work['display_name'] ?? 'Untitled Research', // SAFETY FIX
                            'url' => $url, 
                            'category' => 'Research Paper'
                        ];
                    }
                }
            }
        }
        return $results;
    }

    // =========================================================
    // 2. FIND CITATIONS (WITH CRASH FIX)
    // =========================================================
    public function findCitations($title, $subject = null) {
        $results = [];
        $cleanTitle = $this->cleanTitleForAPI($title);
        
        // --- ATTEMPT 1: Search for Manuscript Title ---
        $query = '"' . $cleanTitle . '"';
        $results = $this->searchOpenAlex($query, 'Academic Citation');

        // --- ATTEMPT 2: Google Books (Title) ---
        if (count($results) < 2) {
            $bookResults = $this->searchGoogleBooks($cleanTitle);
            $results = array_merge($results, $bookResults);
        }

        // --- ATTEMPT 3: Search for SUBJECT if list is empty ---
        if (count($results) == 0 && !empty($subject)) {
            $cleanSubject = $this->cleanTitleForAPI($subject);
            $subjectResults = $this->searchOpenAlex($cleanSubject, 'Subject Reference');
            $results = array_merge($results, $subjectResults);
        }
        
        return array_slice($results, 0, 6); 
    }

    // --- WORKER: OpenAlex Search ---
    private function searchOpenAlex($query, $categoryName) {
        $found = [];
        $apiUrl = "https://api.openalex.org/works?search=" . urlencode($query) . "&per-page=5";
        $json = $this->fetchJSON($apiUrl);
        
        if ($json) {
            $data = json_decode($json, true);
            if (!empty($data['results'])) {
                foreach ($data['results'] as $work) {
                    $url = $work['doi'] ?? $work['landing_page_url'] ?? ($work['id'] ?? null);
                    
                    // --- THE FIX IS HERE ---
                    // If display_name is NULL, use 'Untitled Citation' so DB doesn't crash
                    $title = $work['display_name'] ?? 'Untitled Citation';
                    
                    if ($url) {
                         $found[] = [
                            'title' => $title,
                            'url' => $url,
                            'category' => $categoryName
                        ];
                    }
                }
            }
        }
        return $found;
    }

    // --- WORKER: Google Books Search ---
    private function searchGoogleBooks($query) {
        $found = [];
        $booksUrl = "https://www.googleapis.com/books/v1/volumes?q=" . urlencode($query);
        $jsonBooks = $this->fetchJSON($booksUrl);
        
        if ($jsonBooks) {
            $bookData = json_decode($jsonBooks, true);
            if (!empty($bookData['items'])) {
                foreach ($bookData['items'] as $book) {
                    $info = $book['volumeInfo'];
                    $link = $info['previewLink'] ?? $info['infoLink'];
                    
                    // Safety check for title
                    $title = isset($info['title']) ? "Cited in: " . $info['title'] : "Book Mention";
                    
                    $found[] = [
                        'title' => $title,
                        'url' => $link,
                        'category' => 'Book Mention'
                    ];
                    if (count($found) >= 3) break;
                }
            }
        }
        return $found;
    }

    // --- HELPERS ---
    private function isLinkAlive($url) {
        if (strpos($url, 'openalex.org') !== false) return true;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 4);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ($code >= 200 && $code < 400) || $code == 403 || $code == 503;
    }

    private function fetchJSON($url) {
        $ch = curl_init();
        $email = "student@university.edu"; 
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "ManuHub/1.0 (mailto:$email)");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    private function cleanTitleForAPI($title) {
        $title = preg_replace('/\(.*?\)|\[.*?\]/', '', $title);
        $title = preg_replace('/[^a-zA-Z0-9\s]/', ' ', $title);
        $words = explode(' ', trim($title));
        $significantWords = array_filter($words, function($w) { return strlen($w) > 3; });
        return implode(' ', array_slice($significantWords, 0, 5));
    }
}
?>