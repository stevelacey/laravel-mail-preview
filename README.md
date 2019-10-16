Laravel Mail Preview
====================

[![Latest Version on Packagist](https://img.shields.io/packagist/v/stevelacey/laravel-mail-preview.svg?style=flat-square)](https://packagist.org/packages/stevelacey/laravel-mail-preview)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/stevelacey/laravel-mail-preview.svg?style=flat-square)](https://packagist.org/packages/stevelacey/laravel-mail-preview)

This package introduces a new `preview` mail driver for Laravel that when selected will render the content of the
sent email and save it as both `.html` and `.eml` files.

Installation
------------

Begin by installing the package through Composer. Run the following command in your terminal:

```bash
composer require stevelacey/laravel-mail-preview
```

Then publish the config file:

```
php artisan vendor:publish --provider="Steve\LaravelMailPreview\MailPreviewServiceProvider"
```

Finally, change `MAIL_DRIVER` to `preview` in your `.env` file:

```
MAIL_DRIVER=preview
```

How it works
------------

Everytime an email is sent, an `.html` and `.eml` file will be generated in `storage/email-previews` with a name that includes the first recipient and the subject:

```
1457904864_jack_at_gmail_com_invoice_000234.html
1457904864_jack_at_gmail_com_invoice_000234.eml
```

You can open the `.html` file in a web browser, or open the `.eml` file in your default email client to have a realistic look
at the final output.

### Preview in a web browser

When you open the `.html` file in a web browser you'll be able to see how your email will look, however there might be
some differences that varies from one email client to another.
