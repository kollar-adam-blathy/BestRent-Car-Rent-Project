$ErrorActionPreference = 'Stop'

$installerDir = Split-Path -Parent $MyInvocation.MyCommand.Path
$projectDir = Split-Path -Parent $installerDir

Set-Location $projectDir

Write-Host 'Windows build indul...'
dotnet build -f net10.0-windows10.0.19041.0
if ($LASTEXITCODE -ne 0) {
    throw 'A Windows build nem sikerult.'
}

$isccCandidates = @(
    "$env:ProgramFiles(x86)\Inno Setup 6\ISCC.exe",
    "$env:ProgramFiles\Inno Setup 6\ISCC.exe",
    "$env:LOCALAPPDATA\Programs\Inno Setup 6\ISCC.exe"
)

$isccPath = $isccCandidates | Where-Object { Test-Path $_ } | Select-Object -First 1

if (-not $isccPath) {
    throw 'Az Inno Setup Compiler (ISCC.exe) nincs telepitve. Telepitsd az Inno Setup 6-ot: https://jrsoftware.org/isinfo.php'
}

Write-Host 'Installer forditas indul...'
& $isccPath (Join-Path $installerDir 'BestRentMobile.iss')
if ($LASTEXITCODE -ne 0) {
    throw 'Az installer letrehozasa nem sikerult.'
}

Write-Host 'Kesz. Az installer itt lesz:'
Write-Host (Join-Path $installerDir 'Output')
