# PHP Printer Service

## Introduction

The PHP Printer Service allows you to easily set up a local PHP-based printer service on your Windows system. This service enables you to print documents directly from your web applications to a local printer.

## Installation

To use the PHP Printer Service, follow these installation steps:

1. Download the PHP 8.0.30 Non-Thread Safe (NTS) for Windows x64 package from the [official PHP downloads page](https://windows.php.net/download/) or an alternative trusted source.

2. Extract the contents of the downloaded PHP package to a directory. For example, you can copy the contents to `C:\Program Files\php-8.0.30-nts-Win32-vs16-x64`.

3. Next, you need to set an environmental variable for the `PATH` to include the PHP directory. To do this:

   - Open the Windows Start menu.
   - Search for "Environment Variables" and select "Edit the system environment variables."
   - In the "System Properties" window, click the "Environment Variables" button.
   - Under the "System variables" section, find the "Path" variable and click "Edit."
   - Click "New" and add the path to the PHP directory (e.g., `C:\Program Files\php-8.0.30-nts-Win32-vs16-x64`).
   - Click "OK" to save your changes.

## Running the PHP Printer Service

Now that you have PHP installed and the environmental variable set, you can run the PHP Printer Service. Here's how:

1. Download the `escpos-php-development` project and extract it to a directory, such as `C:\`.

2. Open a command prompt or terminal and navigate to the directory where you extracted the `escpos-php-development` project.

3. Start a local PHP web server by running the following command:

   ```bash
   php -S localhost:8080
