# Get the directory where the script is located
$scriptDirectory = Split-Path -Parent $MyInvocation.MyCommand.Path

# Define the parent directory where you want to save sprite.svg
$parentDirectory = Split-Path -Parent $scriptDirectory

# Define a function to process SVG files in a directory and its subdirectories
function Process-SvgFiles {
    param (
        [string]$directory,
        [string]$svgContent,
        [string]$subdirectoryPrefix = ""
    )

    # Get a list of all SVG files in the current directory
    $svgFiles = Get-ChildItem -Path $directory -Filter *.svg

    foreach ($file in $svgFiles) {
        # Load the SVG file into a XmlDocument object
        $fileContent = Get-Content $file.FullName -Encoding UTF8
        $svgFile = [xml]$fileContent

        if ($svgFile) {
            $matches = [regex]::Match($svgFile.OuterXml, '<svg.*?>(.*?)<\/svg>', [System.Text.RegularExpressions.RegexOptions]::Singleline)
            if ($matches.Success) {
                $svgContent += '<symbol id="' + $subdirectoryPrefix + [System.IO.Path]::GetFileNameWithoutExtension($file.Name) + '" viewBox="' + $svgFile.DocumentElement.viewBox + '" fill="' + $svgFile.DocumentElement.fill + '">' + [System.Environment]::NewLine
                $svgContent += $matches.Groups[1].Value.Trim() + [System.Environment]::NewLine
                $svgContent += '</symbol>' + [System.Environment]::NewLine
            }
        }
    }

    # Recursively process subdirectories
    $subdirectories = Get-ChildItem -Path $directory -Directory
    foreach ($subdirectory in $subdirectories) {
        $subdirectoryPrefix2 = "$subdirectoryPrefix$subdirectory-"
        $svgContent = Process-SvgFiles -directory $subdirectory.FullName -svgContent $svgContent -subdirectoryPrefix $subdirectoryPrefix2
    }

    return $svgContent
}

# Initialize the SVG document
$svg = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">'

# Call the function to process SVG files in the script's directory and its subdirectories
$svg = Process-SvgFiles -directory $scriptDirectory -svgContent $svg

$svg += '</svg>'

# Define the path where sprite.svg will be saved
$spriteFilePath = Join-Path -Path $parentDirectory -ChildPath "svg_sprite.svg"

# Delete existing sprite.svg if it exists
if (Test-Path -Path $spriteFilePath -PathType Leaf) {
    Remove-Item -Path $spriteFilePath -Force
}

# Create a new sprite.svg file
$doc = New-Object System.Xml.XmlDocument
$doc.LoadXml($svg)
$doc.PreserveWhitespace = $false
$doc.Save($spriteFilePath)