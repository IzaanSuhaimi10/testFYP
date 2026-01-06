<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Researcher Registration - ManuHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; }
        .register-card { max-width: 500px; border: none; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
        .btn-primary { background-color: #6d0828; border: none; padding: 12px; font-weight: 600; }
        .btn-primary:hover { background-color: #4d061c; }
        .form-label { font-size: 13px; font-weight: 600; color: #555; }
        .logo-img { width: 100px; border-radius: 10px; margin-bottom: 15px; }
    </style>
    <script>
        function validateForm() {
            const username = document.forms["registerForm"]["username"].value;
            const email = document.forms["registerForm"]["email"].value;
            const password = document.forms["registerForm"]["password"].value;
            const confirmPassword = document.forms["registerForm"]["confirm_password"].value;
            const fileInput = document.forms["registerForm"]["identity_doc"].value;

            if (/<.*?>/.test(username)) { alert("Username cannot contain HTML tags."); return false; }
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { alert("Enter a valid email."); return false; }
            if (password.length < 8 || password.length > 16) { alert("Password must be 8-16 characters."); return false; }
            if (password !== confirmPassword) { alert("Passwords do not match."); return false; }
            if (!fileInput) { alert("Please upload your Institutional ID."); return false; }
            return true;
        }
    </script>
</head>
<body class="d-flex align-items-center py-5 min-vh-100">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card register-card p-4 p-md-5 mx-auto">
                    <div class="text-center">
                        <a href="/manuhub/public">
                            <img src="../assets/images/manuhub_logo.jpeg" alt="ManuHub Logo" class="logo-img">
                        </a>
                        <h2 class="h4 fw-bold mb-2">Researcher Registration</h2>
                        <p class="text-muted small mb-4">Accounts are reviewed by experts before activation.</p>
                    </div>

                    <?php if (isset($data['error'])): ?>
                        <div class="alert alert-danger py-2 small text-center"><?php echo $data['error']; ?></div>
                    <?php endif; ?>

                    <form name="registerForm" method="POST" action="/manuhub/public/index.php?action=register" enctype="multipart/form-data" onsubmit="return validateForm()">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" required placeholder="name@university.edu">
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" required placeholder="ResearcherName">
                            </div>

                            <div class="col-12">
                                <div class="p-3 border rounded bg-light">
                                    <label class="form-label mb-1">Upload Institutional ID</label>
                                    <p class="text-muted" style="font-size: 11px;">Required for identity verification (PDF/JPG/PNG)</p>
                                    <input type="file" name="identity_doc" class="form-control form-control-sm" required>
                                </div>
                            </div>

                            <div class="col-12">
    <label class="form-label">Reason for joining ManuHub</label>
    <div class="p-3 border rounded bg-white">
        <div class="form-check mb-2">
            <input class="form-check-input" type="radio" name="join_reason_type" id="reason1" value="I want to be a researcher" checked onclick="toggleOther(false)">
            <label class="form-check-label small" for="reason1">I want to be a researcher</label>
        </div>
        <div class="form-check mb-2">
            <input class="form-check-input" type="radio" name="join_reason_type" id="reason2" value="Want to contribute to metadata completion" onclick="toggleOther(false)">
            <label class="form-check-label small" for="reason2">Want to contribute to metadata completion</label>
        </div>
        <div class="form-check mb-2">
            <input class="form-check-input" type="radio" name="join_reason_type" id="reason3" value="Reference for my own research" onclick="toggleOther(false)">
            <label class="form-check-label small" for="reason3">Reference for my own research</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="join_reason_type" id="reason4" value="Other" onclick="toggleOther(true)">
            <label class="form-check-label small" for="reason4">Other</label>
        </div>
        
        <div id="other_reason_container" class="mt-2" style="display: none;">
            <textarea name="other_reason_text" class="form-control form-control-sm" placeholder="Please specify your reason..."></textarea>
        </div>
    </div>
</div>

<script>
    function toggleOther(show) {
        document.getElementById('other_reason_container').style.display = show ? 'block' : 'none';
    }
</script>

                            <div class="col-md-6">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required placeholder="8-16 chars">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="confirm_password" class="form-control" required placeholder="Repeat password">
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary w-100">Request Verification</button>
                            </div>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <hr class="opacity-25">
                        <p class="small text-muted mb-0">Already have an account? <a href="/manuhub/public/index.php?action=login" class="text-decoration-none fw-bold" style="color: #6d0828;">Login</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>