#requires -version 5.1
<#
  PREPARAR-EQUIPO.ps1
  ---------------------------------------------------------------------
  Deja el laboratorio listo para trabajarse SIN terminal y SIN permisos
  de administrador durante la clase.

  Se ejecuta UNA VEZ, antes de clase, con permisos de administrador:
      PREPARAR-EQUIPO.bat

  Si en el equipo faltan PHP, Composer o Node, el preparador los descarga
  en version portable (no depende de winget ni de instaladores con GUI)
  y los guarda en una carpeta comun del equipo:
      ProgramData\LabLaravel11\tools

  Opciones:
      -Destino <ruta>      Carpeta del proyecto.
                           Por defecto: Documentos\LabLaravel11\lab-formularios
      -Herramientas <ruta> Carpeta de las herramientas portables.
                           Por defecto: ProgramData\LabLaravel11\tools
      -Puerto <n>          Puerto del servidor local (por defecto 8000).
      -UsarPortatil        Instala y usa las herramientas portables aunque ya
                           existan en el equipo (aula hermetica / pruebas).
      -SinInstalar         No descarga herramientas: si falta algo, se detiene.
      -SinBuild            No ejecuta npm install / npm run build.
      -SoloVerificar       No instala nada: solo comprueba el estado actual.
      -Forzar              Vuelve a copiar la base y recompila los assets.
      -SinAcceso           No crea el acceso directo del escritorio.

  Al terminar deja dentro del proyecto:
      INICIAR-APP.bat        arranca el servidor y abre el navegador
      DETENER-APP.bat        detiene el servidor
      PROBAR.bat             ejecuta las pruebas
      REINICIAR-DEMO.bat     reinicia el contador del throttle
      VERIFICAR-ENTORNO.bat  comprueba que todo esta en su sitio
#>
[CmdletBinding()]
param(
    [string]$Destino,
    [string]$Herramientas,
    [int]$Puerto = 8000,
    [switch]$UsarPortatil,
    [switch]$SinInstalar,
    [switch]$SinBuild,
    [switch]$SoloVerificar,
    [switch]$Forzar,
    [switch]$SinAcceso
)

$ErrorActionPreference = 'Stop'
$Kit = Split-Path $PSScriptRoot -Parent

if (-not $Destino) {
    $Destino = Join-Path ([Environment]::GetFolderPath('MyDocuments')) 'LabLaravel11\lab-formularios'
}
$Destino = [IO.Path]::GetFullPath($Destino)

if (-not $Herramientas) {
    $Herramientas = Join-Path $env:ProgramData 'LabLaravel11\tools'
}
$Herramientas = [IO.Path]::GetFullPath($Herramientas)

function Linea { param([string]$Texto = '') Write-Host $Texto }
function Ok     { param([string]$Texto) Write-Host ('  [OK]    ' + $Texto) -ForegroundColor Green }
function Aviso  { param([string]$Texto) Write-Host ('  [AVISO] ' + $Texto) -ForegroundColor Yellow }
function Fallo  { param([string]$Texto) Write-Host ('  [ERROR] ' + $Texto) -ForegroundColor Red }
function Paso   { param([string]$Texto) Write-Host ''; Write-Host ('== ' + $Texto) -ForegroundColor Cyan }

$script:Php      = $null
$script:Composer = $null
$script:Node     = $null
$script:Npm      = $null
$script:Tools    = $Herramientas

# ---------------------------------------------------------------------------
# Descarga portable
# ---------------------------------------------------------------------------
function Descargar {
    param([string]$Url, [string]$Destino)

    [Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls12
    $previo = $ProgressPreference
    $ProgressPreference = 'SilentlyContinue'
    try {
        Invoke-WebRequest -Uri $Url -OutFile $Destino -UseBasicParsing -TimeoutSec 900
    } finally {
        $ProgressPreference = $previo
    }

    if (-not (Test-Path $Destino) -or (Get-Item $Destino).Length -eq 0) {
        throw "No se pudo descargar: $Url"
    }
}

function Test-ZipValido {
    param([string]$Ruta)
    $bytes = [IO.File]::ReadAllBytes($Ruta)
    return ($bytes.Length -gt 4 -and $bytes[0] -eq 0x50 -and $bytes[1] -eq 0x4B)
}

function Instalar-PhpPortable {
    $dir = Join-Path $script:Tools 'php'
    if (Test-Path (Join-Path $dir 'php.exe')) { return $dir }

    New-Item -ItemType Directory -Path $dir -Force | Out-Null
    $zip = Join-Path $env:TEMP 'lab-php.zip'
    Linea '  Descargando PHP 8.4 portable...'
    Descargar 'https://windows.php.net/downloads/releases/latest/php-8.4-nts-Win32-vs17-x64-latest.zip' $zip
    if (-not (Test-ZipValido $zip)) { throw 'La descarga de PHP no es un ZIP valido.' }

    Expand-Archive -Path $zip -DestinationPath $dir -Force
    Remove-Item $zip -Force

    if (-not (Test-Path (Join-Path $dir 'php.exe'))) {
        throw 'El ZIP de PHP no traia php.exe en la raiz.'
    }

    # php.ini minimo con las extensiones que usa Laravel y Composer.
    $ini = Join-Path $dir 'php.ini'
    $lineas = @(
        '[PHP]',
        "extension_dir = `"$dir\ext`"",
        'extension=curl',
        'extension=fileinfo',
        'extension=mbstring',
        'extension=openssl',
        'extension=zip',
        'date.timezone = UTC',
        'memory_limit = 512M',
        'upload_max_filesize = 8M',
        'post_max_size = 16M'
    )
    Set-Content -Path $ini -Value ($lineas -join "`r`n") -Encoding ASCII
    return $dir
}

function Instalar-ComposerPortable {
    $dir = Join-Path $script:Tools 'composer'
    New-Item -ItemType Directory -Path $dir -Force | Out-Null
    $phar = Join-Path $dir 'composer.phar'
    if (Test-Path $phar) { return $phar }

    Linea '  Descargando Composer...'
    Descargar 'https://getcomposer.org/composer-stable.phar' $phar
    return $phar
}

function Instalar-NodePortable {
    $dir = Join-Path $script:Tools 'node'
    if (Test-Path (Join-Path $dir 'node.exe')) { return $dir }

    New-Item -ItemType Directory -Path $dir -Force | Out-Null
    Linea '  Consultando la ultima version LTS de Node...'
    $indice = Invoke-RestMethod -Uri 'https://nodejs.org/dist/index.json' -TimeoutSec 120
    $lts = $indice | Where-Object { $_.lts } | Select-Object -First 1
    if (-not $lts) { throw 'No se pudo determinar la version LTS de Node.' }

    $url = "https://nodejs.org/dist/$($lts.version)/node-$($lts.version)-win-x64.zip"
    Linea "  Descargando Node $($lts.version) portable..."
    $zip = Join-Path $env:TEMP 'lab-node.zip'
    Descargar $url $zip
    if (-not (Test-ZipValido $zip)) { throw 'La descarga de Node no es un ZIP valido.' }

    $tmp = Join-Path $env:TEMP ('lab-node-' + [guid]::NewGuid().ToString('N'))
    Expand-Archive -Path $zip -DestinationPath $tmp -Force
    $inner = Get-ChildItem $tmp -Directory | Select-Object -First 1
    if (-not $inner) { throw 'El ZIP de Node no traia la carpeta esperada.' }

    Copy-Item (Join-Path $inner.FullName '*') $dir -Recurse -Force
    Remove-Item $tmp -Recurse -Force
    Remove-Item $zip -Force

    if (-not (Test-Path (Join-Path $dir 'node.exe'))) {
        throw 'El ZIP de Node no traia node.exe.'
    }
    return $dir
}

function Agregar-PathSesion {
    $dirs = @()
    if ($script:Php)  { $dirs += (Split-Path $script:Php -Parent) }
    if ($script:Node) { $dirs += (Split-Path $script:Node -Parent) }
    $dirs = $dirs | Where-Object { $_ -and (Test-Path $_) } | Select-Object -Unique
    if ($dirs.Count -gt 0) {
        $env:PATH = (($dirs -join ';') + ';' + $env:PATH)
    }
}

# ---------------------------------------------------------------------------
# Deteccion y validacion
# ---------------------------------------------------------------------------
function Buscar-Php {
    $cmd = Get-Command php -ErrorAction SilentlyContinue
    if ($cmd) { return $cmd.Source }

    $rutas = @()
    $rutas += Get-ChildItem -Path 'C:\php*\php.exe' -ErrorAction SilentlyContinue
    $rutas += Get-ChildItem -Path 'C:\xampp\php\php.exe' -ErrorAction SilentlyContinue
    $rutas += Get-ChildItem -Path 'C:\laragon\bin\php\*\php.exe' -ErrorAction SilentlyContinue
    $rutas += Get-ChildItem -Path 'C:\wamp64\bin\php\*\php.exe' -ErrorAction SilentlyContinue

    $portatil = Join-Path $script:Tools 'php\php.exe'
    if (Test-Path $portatil) { $rutas += Get-Item $portatil }

    if ($rutas.Count -eq 0) { return $null }
    return ($rutas | Sort-Object FullName -Descending | Select-Object -First 1).FullName
}

function Buscar-Composer {
    $cmd = Get-Command composer -ErrorAction SilentlyContinue
    if ($cmd) { return $cmd.Source }

    $junto = Join-Path (Split-Path $script:Php) 'composer.phar'
    if (Test-Path $junto) { return $junto }

    $portatil = Join-Path $script:Tools 'composer\composer.phar'
    if (Test-Path $portatil) { return $portatil }
    return $null
}

function Obtener-VersionPhp {
    $salida = Invocar-Nativo { & $script:Php -r 'echo PHP_VERSION;' }
    return ($salida.Trim() -replace '[^0-9.].*$', '')
}

function Test-VersionPhp {
    param([string]$Version)
    try { return ([version]$Version) -ge [version]'8.2.0' } catch { return $false }
}

function Test-ExtensionesPhp {
    $salida = Invocar-Nativo { & $script:Php -m }
    $mods = $salida -split '\r?\n' | ForEach-Object { $_.Trim().ToLower() }
    return @('fileinfo', 'mbstring', 'openssl') | Where-Object { $mods -notcontains $_ }
}

function Probar-Composer {
    if (-not $script:Php -or -not $script:Composer) { return $false }
    if ($script:Composer -like '*.phar') {
        $salida = Invocar-Nativo { & $script:Php $script:Composer --version }
    } else {
        $salida = Invocar-Nativo { & $script:Composer --version }
    }
    return ($LASTEXITCODE -eq 0 -and $salida -match 'Composer version')
}

function Probar-Node {
    if (-not $script:Node) { return $false }
    $salida = Invocar-Nativo { & $script:Node -v }
    if ($salida -match 'v(\d+)') { return ([int]$Matches[1] -ge 18) }
    return $false
}

function Asegurar-Herramientas {
    $script:Php      = Buscar-Php
    $script:Composer = Buscar-Composer
    $script:Node     = (Get-Command node -ErrorAction SilentlyContinue).Source
    $script:Npm      = (Get-Command npm -ErrorAction SilentlyContinue).Source

    $phpOk      = $false
    $composerOk = $false
    $nodeOk     = $false

    if ($script:Php) {
        $version = Obtener-VersionPhp
        $phpOk = (Test-VersionPhp $version)
        if (-not $phpOk) {
            Aviso "PHP $version encontrado, pero se requiere 8.2 o superior."
        } else {
            $faltan = Test-ExtensionesPhp
            if ($faltan) {
                Aviso ('Faltan extensiones de PHP: ' + ($faltan -join ', ') + '.')
                $phpOk = $false
            }
        }
    }

    if ($script:Composer) { $composerOk = Probar-Composer }
    if ($script:Node)     { $nodeOk = Probar-Node }

    if ($UsarPortatil) {
        Aviso 'Modo portable forzado (-UsarPortatil): se ignoran las herramientas del sistema.'
        $phpOk = $false
        $composerOk = $false
        $nodeOk = $false
    }

    if ($phpOk -and $composerOk -and $nodeOk) {
        Agregar-PathSesion
        return
    }

    if ($SinInstalar) {
        if (-not $phpOk)      { Fallo 'PHP no valido o inexistente. Quita -SinInstalar para instalarlo.' }
        if (-not $composerOk) { Fallo 'Composer no disponible. Quita -SinInstalar para instalarlo.' }
        if (-not $nodeOk)     { Fallo 'Node.js 18+ no disponible. Quita -SinInstalar para instalarlo.' }
        exit 1
    }

    Linea ''
    Linea '  Instalando herramientas portables en:'
    Linea "    $script:Tools"
    Linea '  (solo la primera vez; luego se reutilizan)'

    if (-not $phpOk) {
        $dir = Instalar-PhpPortable
        $script:Php = Join-Path $dir 'php.exe'
        Agregar-PathSesion
        $version = Obtener-VersionPhp
        if (-not (Test-VersionPhp $version)) { Fallo "PHP portable no arranco (version: $version)."; exit 1 }
    }

    if (-not $composerOk) {
        $script:Composer = Instalar-ComposerPortable
    }

    if (-not $nodeOk) {
        $dirNode = Instalar-NodePortable
        $script:Node = Join-Path $dirNode 'node.exe'
        $script:Npm  = Join-Path $dirNode 'npm.cmd'
        Agregar-PathSesion
    }

    # Revalidar todo lo instalado.
    $script:Composer = if ($script:Php) { Buscar-Composer } else { $null }
    if (-not (Probar-Composer)) { Fallo 'Composer no responde despues de la instalacion.'; exit 1 }
    if (-not (Probar-Node))     { Fallo 'Node no responde despues de la instalacion.'; exit 1 }
    $faltan = Test-ExtensionesPhp
    if ($faltan) { Fallo ('Faltan extensiones despues de instalar PHP: ' + ($faltan -join ', ')); exit 1 }
}

function Invocar-Nativo {
    # Ejecuta un comando nativo sin que stderr se convierta en error
    # (ErrorActionPreference='Stop' vuelve agresivo el stderr redirigido).
    param([scriptblock]$Bloque)
    $previo = $ErrorActionPreference
    $ErrorActionPreference = 'Continue'
    try { return (& $Bloque 2>&1 | Out-String) }
    finally { $ErrorActionPreference = $previo }
}

function Invocar-Artisan {
    param([string[]]$Argumentos)
    Push-Location $Destino
    try {
        $previo = $ErrorActionPreference
        $ErrorActionPreference = 'Continue'
        try {
            & $script:Php artisan @Argumentos 2>&1 | ForEach-Object { Write-Host $_ }
            $codigo = $LASTEXITCODE
        } finally { $ErrorActionPreference = $previo }
    } finally { Pop-Location }
    return $codigo
}

function Escribir-Lanzadores {
    $plantillas = Join-Path $PSScriptRoot 'plantillas'
    $config = "@echo off`r`nset `"PHP=$script:Php`"`r`nset `"PUERTO=$Puerto`"`r`n"
    Set-Content -Path (Join-Path $Destino 'lab-config.bat') -Value $config -Encoding ASCII

    foreach ($archivo in @('INICIAR-APP.bat', 'DETENER-APP.bat', 'PROBAR.bat', 'REINICIAR-DEMO.bat', 'VERIFICAR-ENTORNO.bat')) {
        $origen = Join-Path $plantillas $archivo
        if (Test-Path $origen) {
            Copy-Item $origen (Join-Path $Destino $archivo) -Force
        } else {
            Aviso "Falta la plantilla $archivo"
        }
    }
    Ok 'Lanzadores de doble clic creados.'
}

function Crear-AccesoDirecto {
    if ($SinAcceso) { return }
    try {
        $escritorio = [Environment]::GetFolderPath('Desktop')
        $lnk = Join-Path $escritorio 'Lab Laravel 11.lnk'
        $ws = New-Object -ComObject WScript.Shell
        $acceso = $ws.CreateShortcut($lnk)
        $acceso.TargetPath = Join-Path $Destino 'INICIAR-APP.bat'
        $acceso.WorkingDirectory = $Destino
        $acceso.Description = 'Arranca el laboratorio de formularios (Laravel 11)'
        $acceso.IconLocation = "$script:Php,0"
        $acceso.Save()
        Ok "Acceso directo creado: $lnk"
    } catch {
        Aviso 'No se pudo crear el acceso directo del escritorio.'
    }
}

function Probar-Servidor {
    $puertoPrueba = 8099
    Get-NetTCPConnection -LocalPort $puertoPrueba -State Listen -ErrorAction SilentlyContinue |
        ForEach-Object { Stop-Process -Id $_.OwningProcess -Force -ErrorAction SilentlyContinue }

    $proc = Start-Process -FilePath $script:Php `
        -ArgumentList 'artisan', 'serve', '--host=127.0.0.1', "--port=$puertoPrueba" `
        -WorkingDirectory $Destino -WindowStyle Hidden -PassThru

    $getOk = $false
    try {
        for ($i = 0; $i -lt 30; $i++) {
            Start-Sleep -Milliseconds 500
            try {
                $r = Invoke-WebRequest -Uri "http://127.0.0.1:$puertoPrueba/products/create" -UseBasicParsing -TimeoutSec 3
                if ($r.StatusCode -eq 200 -and $r.Content -match 'data-product-form') { $getOk = $true; break }
            } catch { }
        }

        if (-not $getOk) {
            Fallo 'El formulario no respondio en 15 segundos.'
            return $false
        }
        Ok 'GET /products/create responde 200 con el formulario.'

        $codigo = 0
        try {
            Invoke-WebRequest -Uri "http://127.0.0.1:$puertoPrueba/products" -Method Post `
                -Body @{ code = 'PRD001'; name = 'Prueba'; price = '10' } `
                -UseBasicParsing -TimeoutSec 5 | Out-Null
            $codigo = 200
        } catch {
            if ($_.Exception.Response) { $codigo = [int]$_.Exception.Response.StatusCode }
            else { $codigo = -1 }
        }

        if ($codigo -eq 419) {
            Ok 'POST sin token responde 419 (defensa CSRF activa).'
        } elseif ($codigo -eq 302 -or $codigo -eq 200) {
            Aviso "POST sin token respondio $codigo. La vista ya tiene @csrf (alumno avanzado)."
        } else {
            Aviso "POST sin token respondio $codigo (se esperaba 419)."
        }
        return $true
    } finally {
        if ($proc -and -not $proc.HasExited) { Stop-Process -Id $proc.Id -Force -ErrorAction SilentlyContinue }
        Get-NetTCPConnection -LocalPort $puertoPrueba -State Listen -ErrorAction SilentlyContinue |
            ForEach-Object { Stop-Process -Id $_.OwningProcess -Force -ErrorAction SilentlyContinue }
    }
}

function Estado-General {
    $todoOk = $true

    if (-not (Test-Path $script:Php)) {
        Fallo 'PHP no encontrado.'
        $todoOk = $false
    } else {
        $version = Obtener-VersionPhp
        if (Test-VersionPhp $version) {
            Ok "PHP $version - $script:Php"
        } else {
            Fallo "PHP $version (se requiere 8.2+)."
            $todoOk = $false
        }
        $faltan = Test-ExtensionesPhp
        if ($faltan) {
            Fallo ('Faltan extensiones: ' + ($faltan -join ', '))
            $todoOk = $false
        } else {
            Ok 'Extensiones fileinfo, mbstring y openssl presentes.'
        }
    }

    if ($script:Composer -and (Probar-Composer)) { Ok "Composer: $script:Composer" }
    else { Fallo 'Composer no responde.'; $todoOk = $false }

    if ($script:Node -and (Probar-Node)) { Ok "Node: $((Invocar-Nativo { & $script:Node -v }).Trim())" }
    else { Fallo 'Node.js 18+ no disponible (los estilos no se compilarian).'; $todoOk = $false }

    if (Test-Path (Join-Path $Destino 'artisan')) { Ok "Proyecto: $Destino" }
    else { Fallo 'El proyecto no existe en la ruta indicada.'; $todoOk = $false }

    if (Test-Path (Join-Path $Destino 'public\build\manifest.json')) { Ok 'Assets compilados (public/build).' }
    else { Fallo 'Faltan los assets: ejecuta sin -SoloVerificar o usa npm run build.'; $todoOk = $false }

    if (Test-Path (Join-Path $Destino 'public\storage')) { Ok 'Enlace public/storage presente.' }
    else { Fallo 'Falta storage:link (requiere administrador).'; $todoOk = $false }

    foreach ($archivo in @('INICIAR-APP.bat', 'DETENER-APP.bat', 'PROBAR.bat', 'REINICIAR-DEMO.bat', 'VERIFICAR-ENTORNO.bat', 'lab-config.bat')) {
        if (-not (Test-Path (Join-Path $Destino $archivo))) {
            Fallo "Falta el lanzador $archivo."
            $todoOk = $false
        }
    }
    if ($todoOk) { Ok 'Los seis lanzadores estan presentes.' }

    $config = Join-Path $Destino 'lab-config.bat'
    if ((Test-Path $config) -and (Get-Content $config -Raw) -notmatch [regex]::Escape("PHP=$script:Php")) {
        Fallo 'lab-config.bat apunta a otro PHP: ejecuta el preparador de nuevo.'
        $todoOk = $false
    }

    if ($todoOk) {
        Paso 'Prueba real del servidor'
        if (-not (Probar-Servidor)) { $todoOk = $false }
    }

    return $todoOk
}

# ---------------------------------------------------------------------------
# Inicio
# ---------------------------------------------------------------------------
Linea ''
Linea '=========================================================='
Linea '  LABORATORIO LARAVEL 11 - PREPARACION DEL EQUIPO'
Linea '=========================================================='

$esAdmin = ([Security.Principal.WindowsPrincipal][Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole(
    [Security.Principal.WindowsBuiltInRole]::Administrator)

if ($SoloVerificar) {
    Paso 'Verificacion (sin cambios)'
    $script:Php      = Buscar-Php
    $script:Composer = Buscar-Composer
    $script:Node     = (Get-Command node -ErrorAction SilentlyContinue).Source
    $script:Npm      = (Get-Command npm -ErrorAction SilentlyContinue).Source
    $resultado = Estado-General
    Linea ''
    if ($resultado) { Ok 'TODO LISTO PARA LA CLASE.'; exit 0 }
    Fallo 'Hay pendientes: vuelve a ejecutar PREPARAR-EQUIPO.bat como administrador.'
    exit 1
}

Paso '1. Herramientas'
Asegurar-Herramientas
Ok "PHP:      $script:Php"
Ok "Composer: $script:Composer"
Ok "Node:     $script:Node"
Ok "npm:      $script:Npm"

if (-not $esAdmin) {
    Aviso 'Sin permisos de administrador: storage:link puede fallar.'
}

Paso '2. Proyecto Laravel 11'
$artisan = Join-Path $Destino 'artisan'
if (-not (Test-Path $artisan)) {
    if ((Test-Path $Destino) -and (Get-ChildItem $Destino -Force | Select-Object -First 1)) {
        Fallo "La carpeta $Destino no esta vacia y no contiene un proyecto Laravel."
        Fallo 'Elige otra ruta con -Destino o vacia esa carpeta.'
        exit 1
    }
    New-Item -ItemType Directory -Path (Split-Path $Destino) -Force | Out-Null

    $env:COMPOSER_NO_SECURITY_BLOCKING = '1'
    Linea '  Creando proyecto (puede tardar 1-2 minutos)...'
    Push-Location (Split-Path $Destino)
    try {
        if ($script:Composer -like '*.phar') {
            & $script:Php $script:Composer create-project laravel/laravel:^11.0 $Destino --no-interaction --prefer-dist
        } else {
            & $script:Composer create-project laravel/laravel:^11.0 $Destino --no-interaction --prefer-dist
        }
    } finally { Pop-Location }

    if (-not (Test-Path $artisan)) {
        Fallo 'No se pudo crear el proyecto. Revisa la conexion a internet.'
        exit 1
    }
    Ok 'Proyecto creado.'
} else {
    Ok 'El proyecto ya existe (no se sobrescribe).'
}

Paso '3. Archivos del laboratorio'
$controlador = Join-Path $Destino 'app\Http\Controllers\ProductController.php'
if ((Test-Path $controlador) -and -not $Forzar) {
    Ok 'La base del laboratorio ya esta copiada.'
} else {
    Copy-Item (Join-Path $Kit '01-base\*') $Destino -Recurse -Force
    Copy-Item (Join-Path $Kit '02-arranque-inseguro\*') $Destino -Recurse -Force
    Ok 'Base visual y arranque inseguro copiados.'
}

Paso '3b. Esqueletos de trabajo'
$esqueletoRequest = Join-Path $Destino 'app\Http\Requests\StoreProductRequest.php'
$esqueletoTest = Join-Path $Destino 'tests\Feature\ProductFormSecurityTest.php'

if (-not (Test-Path $esqueletoRequest) -or $Forzar) {
    New-Item -ItemType Directory -Path (Join-Path $Destino 'app\Http\Requests') -Force | Out-Null
    Copy-Item (Join-Path $Kit '03-practica\app\Http\Requests\StoreProductRequest.php') $esqueletoRequest -Force
    Ok 'StoreProductRequest.php con TODO, listo para escribir.'
} else {
    Ok 'StoreProductRequest.php ya existe (se respeta).'
}

if (-not (Test-Path $esqueletoTest) -or $Forzar) {
    Copy-Item (Join-Path $Kit '03-practica\tests\Feature\ProductFormSecurityTest.php') $esqueletoTest -Force
    Ok 'Pruebas del Reto 8 listas (en rojo hasta completarlas).'
} else {
    Ok 'Las pruebas ya existen (se respetan).'
}

Paso '4. Assets compilados'
$manifest = Join-Path $Destino 'public\build\manifest.json'
$hot = Join-Path $Destino 'public\hot'
if (Test-Path $hot) {
    Remove-Item $hot -Recurse -Force
    Aviso 'Se elimino public/hot (apuntaba a un servidor Vite apagado).'
}
if ((Test-Path $manifest) -and -not $Forzar) {
    Ok 'Los assets ya estaban compilados.'
} elseif ($SinBuild) {
    Aviso 'Se omitio la compilacion (-SinBuild).'
} elseif (-not $script:Npm) {
    Fallo 'No hay npm: la app se vera sin estilos.'
} else {
    Push-Location $Destino
    try {
        Linea '  npm install...'
        & $script:Npm install --no-fund --no-audit
        Linea '  npm run build...'
        & $script:Npm run build
    } finally { Pop-Location }

    if (Test-Path $manifest) { Ok 'Assets compilados.' }
    else { Fallo 'La compilacion no genero public/build/manifest.json.' }
}

Paso '5. Enlace de storage'
$enlace = Join-Path $Destino 'public\storage'
if (Test-Path $enlace) {
    Ok 'El enlace public/storage ya existe.'
} else {
    $codigo = Invocar-Artisan @('storage:link')
    if ($codigo -eq 0 -and (Test-Path $enlace)) {
        Ok 'Enlace public/storage creado.'
    } else {
        Fallo 'No se pudo crear el enlace. Ejecuta este preparador como administrador.'
    }
}

Paso '6. Lanzadores de doble clic'
Escribir-Lanzadores
Crear-AccesoDirecto

Paso '7. Verificacion final'
$resultado = Estado-General

Linea ''
Linea '=========================================================='
if ($resultado) {
    Ok 'EQUIPO LISTO. En clase no hace falta abrir terminal.'
    Linea ''
    Linea '  Doble clic en el acceso "Lab Laravel 11" (o INICIAR-APP.bat).'
    Linea '  Dentro del proyecto: PROBAR.bat y REINICIAR-DEMO.bat.'
} else {
    Fallo 'La preparacion quedo incompleta. Revisa los mensajes de arriba.'
}
Linea '=========================================================='
Linea ''
exit $(if ($resultado) { 0 } else { 1 })
