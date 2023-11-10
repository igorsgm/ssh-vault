<h1 align="center">🗝️ SSH Vault</h1>

<p align="center">A lightweight utility for easy SSH management. Add, list, access and remove connections with simple commands, keeping your SSH config organized. That's it. No more bash aliases, shell history, txt notes or whatever people usually do to store server details.</p>

<p align="center">
    <a href="https://packagist.org/packages/igorsgm/ssh-vault">
        <img src="https://img.shields.io/packagist/v/igorsgm/ssh-vault.svg?style=flat-square" alt="Latest Version on Packagist">
    </a>
    <a href="https://github.com/igorsgm/ssh-vault/actions/workflows/main.yml/badge.svg">
        <img src="https://img.shields.io/github/actions/workflow/status/igorsgm/ssh-vault/main.yml?style=flat-square" alt="Build Status">
    </a>
    <img src="https://img.shields.io/scrutinizer/coverage/g/igorsgm/ssh-vault/master?style=flat-square" alt="Test Coverage">
    <img src="https://img.shields.io/scrutinizer/quality/g/igorsgm/ssh-vault/master?style=flat-square" alt="Code Quality">
    <a href="https://packagist.org/packages/igorsgm/ssh-vault">
        <img src="https://img.shields.io/packagist/dt/igorsgm/ssh-vault.svg?style=flat-square" alt="Total Downloads">
    </a>
</p>

<hr/>

<p align="center">
    <img src="https://github-production-user-asset-6210df.s3.amazonaws.com/14129843/282014261-1f0f6c47-8b40-441e-9a80-99379bf921cc.png" alt="SSH Vault usage sample">
</p>

## ✨ Features
> It just parses and modifies `~/.ssh/config` file. You can continue to use tools that you like and just use this wrapper to add or remove connections from your ssh config file.
> 
- **List hosts:** View the contents of the ssh/config file in list, table or raw format
- **Add new host:** Easily add new SSH connection
- **Remove host:** Remove SSH connection from

## 1️⃣ Installation

- You can install the package via composer:
```bash
composer global require igorsgm/ssh-vault
```

## 2️⃣ Usage
### 📋 Display all SSH connections in a list format:
> ssh-vault hosts:list

Conveniently lists all your SSH connections in an organized, list format. This command is perfect for a quick overview of your SSH hosts, displaying names and hostnames in an easy-to-read manner.

### 🧮 Display all SSH connections in a table format:
> ssh-vault hosts:table

Effortlessly view all SSH connections in a detailed table format. This command provides a comprehensive layout, showcasing host names, host addresses, user names, ports, and remote commands, if any. Ideal for a clear and structured overview of your SSH configurations.
<p align="center">
    <img src="https://github-production-user-asset-6210df.s3.amazonaws.com/14129843/282016032-ced85b9d-de9d-4bba-a263-98b94fda9cf2.png" alt="SSH Vault hosts list and table">
</p>

### 📄 Display all SSH connections in a raw format:
> ssh-vault hosts:raw

This command offers a straightforward way to view all your SSH connections in their raw format. Ideal for those who prefer to see the complete, unfiltered details of their SSH configuration for review or troubleshooting purposes. Simple and handy.
<p align="center">
    <img src="https://github.com/laravel-zero/laravel-zero/assets/14129843/52860542-95dd-411c-a268-88aaba56b574" alt="SSH Vault raw ssh config">
</p>

### 🔗 Add a new SSH connection to your config file
> ssh-vault hosts:add

Streamline the addition of new SSH connections to your config. Interactive inputs for hostname, port, user, and advanced settings like identity file and agent forwarding ensure easy setup and management of remote server access.
<p align="center">
    <img src="https://github.com/laravel-zero/laravel-zero/assets/14129843/b7f75c82-f215-4971-bcd4-b0f748d186b1" alt="SSH Vault raw ssh config">
</p>

### 🚮 Remove SSH connection from config file
> ssh-vault hosts:remove

Efficiently remove one or more SSH connections from your config file using an interactive multi-select menu. This command simplifies decluttering your SSH setup by allowing easy selection and removal of unnecessary connections, ensuring a clean and up-to-date configuration.

### 🌐 SSH to a specific host from your config file
> ssh-vault hosts:connect

Quickly initiate an SSH connection to a specified host from your configuration. Offers a selection menu for easy host choice, and handles connection details, including executing remote commands, efficiently. Ideal for streamlined access to your remote servers.
<p align="center">
    <img src="https://github.com/laravel-zero/laravel-zero/assets/14129843/d5d86036-96a5-4864-a59d-aacf7e1cc816" alt="SSH Vault raw ssh config">
</p>

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Igor Moraes](https://github.com/igorsgm)
- [Gleb Golda](https://github.com/Salmondx)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
