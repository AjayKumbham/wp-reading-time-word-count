# WordPress Plugin Installation Script for Local by Flywheel
# This script copies the plugin to your Local WordPress installation

param(
    [string]$SiteName = "reading-time-test"
)

$PluginSource = $PSScriptRoot
$LocalSitesPath = "$env:USERPROFILE\Local Sites\$SiteName\app\public\wp-content\plugins"
$PluginDestination = "$LocalSitesPath\wp-reading-time-word-count"

Write-Host "`n=== WordPress Plugin Installer ===" -ForegroundColor Cyan
Write-Host "`nPlugin Source: $PluginSource" -ForegroundColor Yellow
Write-Host "Target Location: $PluginDestination" -ForegroundColor Yellow

# Check if Local site exists
if (-not (Test-Path $LocalSitesPath)) {
    Write-Host "`n[ERROR] Local site '$SiteName' not found!" -ForegroundColor Red
    Write-Host "`nPlease ensure:" -ForegroundColor Yellow
    Write-Host "  1. Local by Flywheel is installed" -ForegroundColor White
    Write-Host "  2. You've created a site named '$SiteName'" -ForegroundColor White
    Write-Host "  3. Or run this script with your site name: .\install-to-local.ps1 -SiteName 'your-site-name'" -ForegroundColor White
    Write-Host "`nAvailable Local sites:" -ForegroundColor Cyan
    $localSitesRoot = "$env:USERPROFILE\Local Sites"
    if (Test-Path $localSitesRoot) {
        Get-ChildItem $localSitesRoot -Directory | ForEach-Object { Write-Host "  - $($_.Name)" -ForegroundColor Green }
    }
    exit 1
}

# Remove existing plugin if present
if (Test-Path $PluginDestination) {
    Write-Host "`n[WARNING] Existing plugin found. Removing..." -ForegroundColor Yellow
    Remove-Item -Path $PluginDestination -Recurse -Force
}

# Copy plugin files
Write-Host "`n[INFO] Copying plugin files..." -ForegroundColor Cyan
Copy-Item -Path $PluginSource -Destination $PluginDestination -Recurse -Force -Exclude @('.git', '.gitignore', 'install-to-local.ps1', 'node_modules')

Write-Host "`n[SUCCESS] Plugin installed successfully!" -ForegroundColor Green
Write-Host "`nNext steps:" -ForegroundColor Cyan
Write-Host "  1. Open Local and start your '$SiteName' site." -ForegroundColor White
Write-Host "  2. Click 'Admin' to open the WordPress dashboard." -ForegroundColor White
Write-Host "  3. Go to Plugins -> Installed Plugins." -ForegroundColor White
Write-Host "  4. Activate 'Reading Time & Word Count'." -ForegroundColor White
Write-Host "  5. Configure at Settings -> Reading Time." -ForegroundColor White
Write-Host "`n[INFO] Site address: http://$SiteName.local" -ForegroundColor Yellow
Write-Host ""
