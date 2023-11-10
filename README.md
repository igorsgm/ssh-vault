<h1 align="center">🗝️ SSH Vault</h1>

<p align="center">Manage SSH connections effortlessly with SSH Vault, a lightweight user-friendly tool for adding, listing, accessing, and removing SSH connections. Replace cumbersome bash aliases and notes with this efficient utility for organized SSH config management.</p>

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

- **List Hosts:** View SSH/config file contents in various formats.
- **Add New Host:** Simplify adding new SSH connections.
- **Remove Host:** Keep your SSH config file clean by removing connections.

## 1️⃣ Installation

- You can install the package via composer:
```bash
composer global require igorsgm/ssh-vault
```

## 2️⃣ Usage
### 📋 Display all SSH connections in a list format:
Detailed list format for easy SSH host overview.
```bash
ssh-vault hosts:list
```

### 🧮 Display all SSH connections in a table format:
Comprehensive table layout for SSH connection details.
```bash
ssh-vault hosts:table
```
<p align="center">
    <img src="https://github-production-user-asset-6210df.s3.amazonaws.com/14129843/282016032-ced85b9d-de9d-4bba-a263-98b94fda9cf2.png" alt="SSH Vault hosts list and table">
</p>


### 📄 Display all SSH connections in a raw format:
Unfiltered view of SSH configurations for in-depth review.
```bash
ssh-vault hosts:raw
```
<p align="center">
    <img src="https://github.com/laravel-zero/laravel-zero/assets/14129843/52860542-95dd-411c-a268-88aaba56b574" alt="SSH Vault raw ssh config">
</p>

### 🔗 Add a new SSH connection to your config file
Interactive inputs for quick SSH connection setup.
```bash
ssh-vault hosts:add
```
<p align="center">
    <img src="https://github.com/laravel-zero/laravel-zero/assets/14129843/b7f75c82-f215-4971-bcd4-b0f748d186b1" alt="SSH Vault raw ssh config">
</p>

### 🚮 Remove SSH connection from config file
Interactive multi-select menu for removing SSH connections.
```bash
ssh-vault hosts:remove
```

### 🌐 SSH to a specific host from your config file
Streamlined SSH connection to chosen hosts with detailed options.
```bash
ssh-vault hosts:connect
```
<p align="center">
    <img src="https://github.com/laravel-zero/laravel-zero/assets/14129843/d5d86036-96a5-4864-a59d-aacf7e1cc816" alt="SSH Vault raw ssh config">
</p>
<hr/>

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
