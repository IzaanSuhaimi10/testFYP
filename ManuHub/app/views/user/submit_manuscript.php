<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Manuscript - ManuHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        /* --- GLOBAL RESET (UNIFIED STYLE) --- */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { display: flex; height: 100vh; background-color: #f8f9fa; overflow: hidden; font-size: 14px; }
        
        /* SIDEBAR */
        .sidebar { width: 250px; min-width: 250px; background-color: #fff; border-right: 1px solid #e0e0e0; display: flex; flex-direction: column; padding: 25px; }
        .brand { font-size: 18px; font-weight: 800; margin-bottom: 40px; color: #333; letter-spacing: 0.5px; display: flex; align-items: center; gap: 10px; }
        .brand-logo { width: 32px; height: 32px; background: #e67e22; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 16px;}

        .menu-item { text-decoration: none; color: #666; padding: 12px 15px; margin-bottom: 8px; border-radius: 8px; font-size: 14px; transition: all 0.2s; display: flex; align-items: center; gap: 12px; font-weight: 500; }
        .menu-item:hover { background-color: #fff7ed; color: #e67e22; }
        .menu-item.active { background-color: #ffedd5; color: #c2410c; font-weight: 700; }

        /* MAIN CONTENT */
        .main-content { flex: 1; padding: 30px; overflow-y: auto; background-color: #f8f9fa; display: flex; flex-direction: column; gap: 25px; }

        /* HEADER BANNER */
        .header-banner {
            background: white; border-radius: 16px; padding: 25px;
            display: flex; align-items: center; gap: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03); border: 1px solid #eee;
        }
        .header-icon { 
            width: 60px; height: 60px; background: #fff7ed; 
            border-radius: 12px; display: flex; align-items: center; justify-content: center;
            font-size: 28px; color: #e67e22;
        }

        /* FORM GRID LAYOUT */
        .form-container { display: grid; grid-template-columns: 1fr 1fr; gap: 25px; }
        @media (max-width: 1000px) { .form-container { grid-template-columns: 1fr; } }

        /* CARDS */
        .card {
            background: white; border-radius: 16px; padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02); border: 1px solid #eee;
        }
        .card-header { margin-bottom: 25px; border-bottom: 1px solid #eee; padding-bottom: 15px; }
        .card-title { font-size: 16px; font-weight: 700; color: #333; display: flex; align-items: center; gap: 10px; }
        .card-icon { color: #e67e22; font-size: 18px; }

        /* INPUTS */
        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 12px; font-weight: 700; color: #555; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; }
        .form-control {
            width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; background: #fafafa; transition: 0.2s;
        }
        .form-control:focus { border-color: #e67e22; background: white; outline: none; box-shadow: 0 0 0 3px rgba(230, 126, 34, 0.1); }
        textarea.form-control { resize: none; min-height: 100px; }

        /* FILE UPLOAD BOX */
        .upload-box {
            border: 2px dashed #ddd; border-radius: 12px; padding: 30px; text-align: center;
            background: #fafafa; transition: 0.2s; cursor: pointer;
        }
        .upload-box:hover { border-color: #e67e22; background: #fffefb; }
        .upload-box i { font-size: 40px; color: #ccc; display: block; margin-bottom: 10px; }
        .upload-box p { color: #777; margin: 0; font-size: 13px; }

        /* SUBMIT BUTTON */
        .btn-submit {
            background: #e67e22; color: white; border: none; padding: 15px 40px;
            border-radius: 10px; font-weight: 700; cursor: pointer; font-size: 15px;
            transition: 0.2s; display: inline-flex; align-items: center; gap: 10px;
            box-shadow: 0 4px 15px rgba(230, 126, 34, 0.3);
            margin-top: 10px;
        }
        .btn-submit:hover { background: #d35400; transform: translateY(-2px); }

    </style>
</head>
<body>

   <div class="sidebar">
    <div class="brand">
        <div class="brand-logo"><i class="bi bi-book-half"></i></div>
        MANUHUB
    </div>
    
    <?php $current_action = $_GET['action'] ?? ''; ?>

    <a href="index.php?action=user_dashboard" class="menu-item <?php echo ($current_action == 'user_dashboard') ? 'active' : ''; ?>">
        <i class="bi bi-grid-fill"></i> Dashboard
    </a>

    <a href="index.php?action=my_manuscripts" class="menu-item <?php echo ($current_action == 'my_manuscripts') ? 'active' : ''; ?>">
        <i class="bi bi-file-earmark-text"></i> My Manuscripts
    </a>

    <a href="index.php?action=my_suggestions" class="menu-item <?php echo ($current_action == 'my_suggestions') ? 'active' : ''; ?>">
        <i class="bi bi-pencil-square"></i> My Edits
    </a>

    <a href="index.php?action=my_sources" class="menu-item <?php echo ($current_action == 'my_sources') ? 'active' : ''; ?>">
        <i class="bi bi-link-45deg"></i> My Sources
    </a>

    <a href="index.php?action=my_flags" class="menu-item <?php echo ($current_action == 'my_flags') ? 'active' : ''; ?>">
        <i class="bi bi-flag-fill"></i> My Flags
    </a>

    <div style="border-top: 1px solid #eee; margin: 15px 0;"></div>

    <a href="index.php?action=submit_manuscript" class="menu-item <?php echo ($current_action == 'submit_manuscript') ? 'active' : ''; ?>">
        <i class="bi bi-plus-square-fill"></i> Submit New
    </a>
    
    <a href="index.php?action=user_edit_profile" class="menu-item <?php echo ($current_action == 'user_edit_profile') ? 'active' : ''; ?>">
        <i class="bi bi-person-circle"></i> My Profile
    </a>

    <a href="index.php" class="menu-item">
        <i class="bi bi-house"></i> Home Page
    </a>
    
    <a href="index.php?action=logout" class="menu-item" style="margin-top: auto; color: #c0392b;">
        <i class="bi bi-box-arrow-left"></i> Logout
    </a>
</div>

    <div class="main-content">
        
        <div class="header-banner">
            <div class="header-icon"><i class="bi bi-journal-plus"></i></div>
            <div>
                <h2 style="font-size: 20px; font-weight: 700; color: #333;">Submit New Manuscript</h2>
                <p style="color: #777; font-size: 13px;">Provide bibliographic data and upload a digital copy for expert verification.</p>
            </div>
        </div>

        <form action="index.php?action=submit_manuscript" method="POST" enctype="multipart/form-data">
            <div class="form-container">
                
                <div class="card">
                    <div class="card-header">
                        <div class="card-title"><i class="bi bi-info-circle-fill card-icon"></i> Primary Metadata</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Manuscript Title</label>
                        <input type="text" name="Title" class="form-control" placeholder="Enter formal title" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Full Description</label>
                        <textarea name="Description" class="form-control" placeholder="Context, history, or physical condition..."></textarea>
                    </div>

                    <div class="form-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div>
                            <label class="form-label">Language</label>
                            <input type="text" name="Language" class="form-control" placeholder="e.g. Malay, Arabic">
                        </div>
                        <div>
                            <label class="form-label">Genre</label>
                            <input type="text" name="Genre" class="form-control" placeholder="e.g. History, Religion">
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-title"><i class="bi bi-pin-map-fill card-icon"></i> Bibliographic Details</div>
                    </div>

                    <div class="form-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div>
                            <label class="form-label">Author / Creator</label>
                            <input type="text" name="Author" class="form-control" placeholder="Anonymous or Name">
                        </div>
                        <div>
                            <label class="form-label">Country of Origin</label>
                            <input type="text" name="Country" class="form-control" placeholder="e.g. Malaysia">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Physical Location</label>
                        <input type="text" name="Location_of_Manuscript" class="form-control" placeholder="e.g. National Library of Malaysia">
                    </div>

                    <div class="form-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div>
                            <label class="form-label">Subject</label>
                            <input type="text" name="Subject" class="form-control" placeholder="General Subject">
                        </div>
                        <div>
                            <label class="form-label">Call Number</label>
                            <input type="text" name="Call_Number" class="form-control" placeholder="Library ID (if any)">
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 10px;">
                        <label class="form-label">Digital Copy (PDF / Image)</label>
                        <div class="upload-box" onclick="document.getElementById('fileInput').click()">
                            <i class="bi bi-cloud-arrow-up"></i>
                            <p id="fileName">Click to choose or drag file here</p>
                            <input type="file" name="manuscript_file" id="fileInput" hidden onchange="updateFileName(this)">
                        </div>
                    </div>
                </div>

            </div>

            <div style="text-align: right; padding: 20px 0;">
                <button type="submit" class="btn-submit">
                    <i class="bi bi-check-circle"></i> Submit for Verification
                </button>
            </div>
        </form>

    </div>

    <script>
        function updateFileName(input) {
            const fileName = input.files[0] ? input.files[0].name : "Click to choose or drag file here";
            document.getElementById('fileName').innerText = fileName;
            document.getElementById('fileName').style.color = "#e67e22";
            document.getElementById('fileName').style.fontWeight = "bold";
        }
    </script>

</body>
</html>