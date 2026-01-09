# Check Local Installation and Install Plugin
# Run this after creating a site in Local

Write-Host "`n╔════════════════════════════════════════════════════════════╗" -ForegroundColor Cyan
Write-Host "║  WordPress Reading Time Plugin - Installation Helper      ║" -ForegroundColor Cyan
Write-Host "╚════════════════════════════════════════════════════════════╝`n" -ForegroundColor Cyan

# Check for Local sites
$localSitesPath = "$env:USERPROFILE\Local Sites"

if (-not (Test-Path $localSitesPath)) {
    Write-Host "[ERROR] No Local Sites folder found at: $localSitesPath`n" -ForegroundColor Red
    Write-Host "Please create a site in Local first:`n" -ForegroundColor Yellow
    Write-Host "  1. Open Local by Flywheel" -ForegroundColor White
    Write-Host "  2. Click '+' or 'Create a new site'" -ForegroundColor White
    Write-Host "  3. Name: reading-time-test" -ForegroundColor White
    Write-Host "  4. Environment: Preferred" -ForegroundColor White
    Write-Host "  5. Set admin credentials" -ForegroundColor White
    Write-Host "  6. Click 'Add Site'`n" -ForegroundColor White
    Read-Host "Press Enter to exit"
    exit
}

# List available sites
$sites = Get-ChildItem $localSitesPath -Directory -ErrorAction SilentlyContinue

if (-not $sites) {
    Write-Host "[ERROR] No sites found in Local`n" -ForegroundColor Red
    Write-Host "Please create a site in Local first (see instructions above)`n" -ForegroundColor Yellow
    Read-Host "Press Enter to exit"
    exit
}

Write-Host "[INFO] Available Local Sites:`n" -ForegroundColor Cyan
$index = 1
$siteList = @()

foreach ($site in $sites) {
    $siteList += $site
    Write-Host "  [$index] $($site.Name)" -ForegroundColor Green
    $index++
}

Write-Host ""

# Ask user to select a site
if ($sites.Count -eq 1) {
    $selectedSite = $sites[0]
    Write-Host "[SUCCESS] Auto-selecting only site: $($selectedSite.Name)`n" -ForegroundColor Green
} else {
    $selection = Read-Host "Select a site number (1-$($sites.Count))"
    $selectedIndex = [int]$selection - 1
    
    if ($selectedIndex -lt 0 -or $selectedIndex -ge $sites.Count) {
        Write-Host "`n[ERROR] Invalid selection`n" -ForegroundColor Red
        Read-Host "Press Enter to exit"
        exit
    }
    
    $selectedSite = $siteList[$selectedIndex]
    Write-Host "`n[SUCCESS] Selected: $($selectedSite.Name)`n" -ForegroundColor Green
}

# Check if WordPress is installed
$wpPath = Join-Path $selectedSite.FullName "app\public"
$pluginsPath = Join-Path $wpPath "wp-content\plugins"

if (-not (Test-Path $pluginsPath)) {
    Write-Host "[ERROR] WordPress not fully set up yet in this site`n" -ForegroundColor Red
    Write-Host "Please ensure the site is fully created in Local`n" -ForegroundColor Yellow
    Read-Host "Press Enter to exit"
    exit
}

Write-Host "[SUCCESS] WordPress found at: $wpPath`n" -ForegroundColor Green

# Install plugin
$pluginSource = $PSScriptRoot
$pluginDest = Join-Path $pluginsPath "wp-reading-time-word-count"

Write-Host "[INFO] Installing plugin...`n" -ForegroundColor Cyan

# Remove existing if present
if (Test-Path $pluginDest) {
    Write-Host "[WARNING] Removing existing plugin installation..." -ForegroundColor Yellow
    Remove-Item -Path $pluginDest -Recurse -Force
}

# Copy plugin
try {
    Copy-Item -Path $pluginSource -Destination $pluginDest -Recurse -Force -Exclude @('.git', '.gitignore', '*.ps1', 'node_modules', '.vscode')
    Write-Host "[SUCCESS] Plugin installed successfully!`n" -ForegroundColor Green
} catch {
    Write-Host "[ERROR] Error installing plugin: $_`n" -ForegroundColor Red
    Read-Host "Press Enter to exit"
    exit
}

# Get site URL
$siteUrl = "http://$($selectedSite.Name).local"

Write-Host "╔════════════════════════════════════════════════════════════╗" -ForegroundColor Green
Write-Host "║                    INSTALLATION COMPLETE                   ║" -ForegroundColor Green
Write-Host "╚════════════════════════════════════════════════════════════╝`n" -ForegroundColor Green

Write-Host "[INFO] Plugin Location:" -ForegroundColor Cyan
Write-Host "   $pluginDest`n" -ForegroundColor White

Write-Host "[INFO] Next Steps:`n" -ForegroundColor Cyan
Write-Host "   1. In Local, make sure your site is STARTED." -ForegroundColor White
Write-Host "   2. Click 'Admin' button in Local to open WordPress dashboard." -ForegroundColor White
Write-Host "   3. Or visit: $siteUrl/wp-admin" -ForegroundColor Yellow
Write-Host "   4. Go to: Plugins -> Installed Plugins." -ForegroundColor White
Write-Host "   5. Find: 'Reading Time & Word Count'." -ForegroundColor White
Write-Host "   6. Click: 'Activate'." -ForegroundColor White
Write-Host "   7. Configure at: Settings -> Reading Time.`n" -ForegroundColor White

Write-Host "[SUCCESS] Process completed successfully.`n" -ForegroundColor Green

Read-Host "Press Enter to exit"
