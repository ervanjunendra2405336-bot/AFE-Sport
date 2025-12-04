Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  AFE Sport - GitHub Upload Script" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Cek Git installation
Write-Host "[1/12] Checking Git installation..." -ForegroundColor Yellow
try {
    $gitVersion = git --version
    Write-Host "[OK] $gitVersion" -ForegroundColor Green
} catch {
    Write-Host "[ERROR] Git is NOT installed!" -ForegroundColor Red
    Write-Host "Download from: https://git-scm.com/" -ForegroundColor Yellow
    Write-Host ""
    pause
    exit
}

Write-Host ""
Write-Host "[2/12] Creating .gitignore file..." -ForegroundColor Yellow

# Create main .gitignore
$gitignoreContent = @"
/vendor
/node_modules
/.env
*.sqlite
*.sqlite-journal
/database/*.sqlite
/database/*.sqlite-journal
/storage/*.key
/storage/app/*
!/storage/app/.gitignore
!/storage/app/public
/storage/app/public/*
!/storage/app/public/.gitignore
/storage/framework/cache/*
!/storage/framework/cache/.gitignore
/storage/framework/sessions/*
!/storage/framework/sessions/.gitignore
/storage/framework/testing/*
!/storage/framework/testing/.gitignore
/storage/framework/views/*
!/storage/framework/views/.gitignore
/storage/logs/*
!/storage/logs/.gitignore
/bootstrap/cache/*
!/bootstrap/cache/.gitignore
/.idea
/.vscode
*.swp
.DS_Store
Thumbs.db
/public/hot
/public/storage
/public/build
.phpunit.result.cache
"@

$gitignoreContent | Out-File -FilePath ".gitignore" -Encoding UTF8
Write-Host "[OK] .gitignore created" -ForegroundColor Green

Write-Host ""
Write-Host "[3/12] Creating storage .gitignore files..." -ForegroundColor Yellow

# Create .gitignore in storage folders
$storageFolders = @(
    "storage\app",
    "storage\app\public",
    "storage\framework\cache",
    "storage\framework\sessions",
    "storage\framework\testing",
    "storage\framework\views",
    "storage\logs",
    "bootstrap\cache"
)

$storageGitignore = "*`n!.gitignore"

foreach ($folder in $storageFolders) {
    if (Test-Path $folder) {
        $gitignorePath = Join-Path $folder ".gitignore"
        $storageGitignore | Out-File -FilePath $gitignorePath -Encoding UTF8
        Write-Host "[OK] Created $gitignorePath" -ForegroundColor Green
    } else {
        Write-Host "[SKIP] Folder not found: $folder" -ForegroundColor Yellow
    }
}

Write-Host ""
Write-Host "[4/12] Initializing Git repository..." -ForegroundColor Yellow
if (Test-Path ".git") {
    Write-Host "[SKIP] Git already initialized" -ForegroundColor Yellow
} else {
    git init
    Write-Host "[OK] Git initialized" -ForegroundColor Green
}

Write-Host ""
Write-Host "[5/12] Setting default branch to main..." -ForegroundColor Yellow
git branch -M main
Write-Host "[OK] Branch set to main" -ForegroundColor Green

Write-Host ""
Write-Host "[6/12] Checking files to upload..." -ForegroundColor Yellow
Write-Host ""
git status --short | Select-Object -First 20
Write-Host ""
Write-Host "... (and more files)" -ForegroundColor Gray
Write-Host ""

Write-Host "[7/12] Adding files to Git..." -ForegroundColor Yellow
git add .
Write-Host "[OK] Files added to staging area" -ForegroundColor Green

Write-Host ""
Write-Host "[8/12] Creating commit..." -ForegroundColor Yellow
$commitMessage = "Initial commit - AFE Sport Booking Platform

- Laravel 11.x
- Booking system with real-time availability
- Admin panel with analytics
- Anti-overbooking feature
- 7 sport categories
- Receipt printing system"

git commit -m $commitMessage
Write-Host "[OK] Commit created" -ForegroundColor Green

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "[9/12] GitHub Repository Configuration" -ForegroundColor Yellow
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

$repoUrl = "https://github.com/ervanjunendra2405336-bot/AFE-Sport.git"
Write-Host "Repository URL: $repoUrl" -ForegroundColor White
Write-Host ""

Write-Host ""
Write-Host "[10/12] Adding remote origin..." -ForegroundColor Yellow
try {
    git remote add origin $repoUrl 2>&1 | Out-Null
    Write-Host "[OK] Remote origin added" -ForegroundColor Green
} catch {
    Write-Host "[WARNING] Remote origin already exists, updating..." -ForegroundColor Yellow
    git remote set-url origin $repoUrl
    Write-Host "[OK] Remote origin updated" -ForegroundColor Green
}

Write-Host ""
Write-Host "[11/12] Verifying remote..." -ForegroundColor Yellow
$remoteUrl = git remote get-url origin
Write-Host "[OK] Remote URL: $remoteUrl" -ForegroundColor Green

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "[12/12] Pushing to GitHub..." -ForegroundColor Yellow
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "[!] You will be prompted to login to GitHub" -ForegroundColor Yellow
Write-Host "[!] Use your GitHub username and Personal Access Token" -ForegroundColor Yellow
Write-Host ""
Write-Host "Uploading files..." -ForegroundColor White
Write-Host ""

try {
    git push -u origin main
    Write-Host ""
    Write-Host "========================================" -ForegroundColor Cyan
    Write-Host "  SUCCESS! Upload Complete!" -ForegroundColor Green
    Write-Host "========================================" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "Repository URL: $remoteUrl" -ForegroundColor White
    Write-Host "Branch: main" -ForegroundColor White
    Write-Host ""
    Write-Host "Next steps:" -ForegroundColor Yellow
    Write-Host "1. Visit your repository: $remoteUrl" -ForegroundColor White
    Write-Host "2. Verify all files uploaded correctly" -ForegroundColor White
    Write-Host "3. Add README.md description (optional)" -ForegroundColor White
    Write-Host "4. Share with your team or deploy" -ForegroundColor White
    Write-Host ""
} catch {
    Write-Host ""
    Write-Host "========================================" -ForegroundColor Red
    Write-Host "  Push Failed!" -ForegroundColor Red
    Write-Host "========================================" -ForegroundColor Red
    Write-Host ""
    Write-Host "Possible reasons:" -ForegroundColor Yellow
    Write-Host "1. Authentication failed (wrong username/token)" -ForegroundColor White
    Write-Host "2. Repository URL is incorrect" -ForegroundColor White
    Write-Host "3. No internet connection" -ForegroundColor White
    Write-Host "4. Repository is private and you dont have access" -ForegroundColor White
    Write-Host ""
    Write-Host "Solutions:" -ForegroundColor Yellow
    Write-Host "1. Create Personal Access Token:" -ForegroundColor White
    Write-Host "   - Go to: https://github.com/settings/tokens" -ForegroundColor Gray
    Write-Host "   - Click: Generate new token (classic)" -ForegroundColor Gray
    Write-Host "   - Select: repo (all checkboxes)" -ForegroundColor Gray
    Write-Host "   - Generate and copy token" -ForegroundColor Gray
    Write-Host "   - Use token as password when pushing" -ForegroundColor Gray
    Write-Host ""
    Write-Host "2. Try manual push:" -ForegroundColor White
    Write-Host "   git push -u origin main" -ForegroundColor Gray
    Write-Host ""
    Write-Host "3. Check repository URL:" -ForegroundColor White
    Write-Host "   git remote get-url origin" -ForegroundColor Gray
    Write-Host ""
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
pause
