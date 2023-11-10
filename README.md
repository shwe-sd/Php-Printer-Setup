# PHP Printer Service

## Introduction

The PHP Printer Service allows you to easily set up a local PHP-based printer service on your Windows system. This service enables you to print documents directly from your web applications to a local printer.

## Installation

To use the PHP Printer Service, follow these installation steps:

1. **Download PHP**: First, download the PHP 8.0.30 Non-Thread Safe (NTS) for Windows x64 package from the [official PHP downloads page](https://windows.php.net/download/) or use the php-8.0.30-nts-Win32-vs16-x64 folder.

2. **Install PHP**: Extract the downloaded PHP package to a directory, e.g `C:\Program Files\php-8.0.30-nts-Win32-vs16-x64`.

3. **Set Environmental Variable**: Add an environmental variable for the `PATH` that includes the PHP directory:

   - Open the Windows Start menu.
   - Search for "Environment Variables" and select "Edit the system environment variables."
   - In the "System Properties" window, click the "Environment Variables" button.
   - Under the "System variables" section, find the "Path" variable and click "Edit."
   - Click "New" and add the path to the PHP directory (e.g., `C:\Program Files\php-8.0.30-nts-Win32-vs16-x64`).
   - Click "OK" to save your changes.

4. **Install EPSON TM-T88IV ReStick Driver**:
   - Visit the Epson website to download and install the EPSON TM-T88IV ReStick driver for your specific printer model. Then, test printing.

5. **Configure Printer Sharing**:
   - Open the Windows Control Panel.
   - Go to "Printer Options" or "Devices and Printers."
   - Locate the installed printer (EPSON TM-T88IV ReStick) and right-click on it.
   - Choose "Printer Properties" or "Printer Preferences."
   - In the "Sharing" tab, enable printer sharing and set the share name to 'SDEpsonT88IV'.

## Running the PHP Printer Service

Now that you have PHP installed and the environmental variable set, you can run the PHP Printer Service. Here's how:

1. Download the `escpos-php-development` project and extract it to a directory, e.g `C:\`.

2. Open a command prompt or terminal and navigate to the directory where you extracted the `escpos-php-development` project.

3. Start a local PHP web server by running the following command:

   ```bash
   php -S localhost:8080
