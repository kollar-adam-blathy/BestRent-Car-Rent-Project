#define MyAppName "BestRentMobile"
#define MyAppVersion "1.0"
#define MyAppPublisher "BestRent"
#define MyAppExeName "BestRentMobile.exe"
#define MyAppSourceDir "..\bin\Debug\net10.0-windows10.0.19041.0\win-x64"

[Setup]
AppId={{D1A4D2E9-83FC-4AB8-A0F0-C9D92C1F9E0D}
AppName={#MyAppName}
AppVersion={#MyAppVersion}
AppPublisher={#MyAppPublisher}
DefaultDirName={autopf}\{#MyAppName}
DefaultGroupName={#MyAppName}
DisableProgramGroupPage=yes
OutputDir=Output
OutputBaseFilename=BestRentMobile-Setup
SetupIconFile={#MyAppSourceDir}\appicon.ico
UninstallDisplayIcon={app}\{#MyAppExeName}
Compression=lzma2
SolidCompression=yes
WizardStyle=modern
ArchitecturesAllowed=x64compatible
ArchitecturesInstallIn64BitMode=x64compatible
PrivilegesRequired=admin

[Languages]
Name: "hungarian"; MessagesFile: "compiler:Languages\Hungarian.isl"

[Tasks]
Name: "desktopicon"; Description: "Asztali ikon létrehozása"; GroupDescription: "További ikonok:"; Flags: unchecked

[Files]
Source: "{#MyAppSourceDir}\*"; DestDir: "{app}"; Flags: ignoreversion recursesubdirs createallsubdirs; Excludes: "*.pdb"

[Icons]
Name: "{group}\BestRent mobile admin felület"; Filename: "{app}\{#MyAppExeName}"
Name: "{autodesktop}\BestRent mobile admin felület"; Filename: "{app}\{#MyAppExeName}"; Tasks: desktopicon

[Run]
Filename: "{app}\{#MyAppExeName}"; Description: "BestRent mobile admin felület indítása"; Flags: nowait postinstall skipifsilent
