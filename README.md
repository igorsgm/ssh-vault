<h1 align="center">🗝️ SSH Vault</h1>

<p align="center">Manage SSH connections effortlessly with SSH Vault, a lightweight user-friendly tool for adding, listing, accessing, and removing SSH connections. Replace cumbersome bash aliases and notes with this efficient utility for organized SSH config management.</p>

<p align="center">
    <a href="https://packagist.org/packages/igorsgm/ssh-vault">
        <img src="https://img.shields.io/packagist/v/igorsgm/ssh-vault.svg?style=flat-square" alt="Latest Version on Packagist">
    </a>
    <img src="https://img.shields.io/scrutinizer/build/g/igorsgm/ssh-vault/master?style=flat-square" alt="Build Status">
    <img src="https://img.shields.io/scrutinizer/coverage/g/igorsgm/ssh-vault/master?style=flat-square" alt="Test Coverage">
    <img src="https://img.shields.io/scrutinizer/quality/g/igorsgm/ssh-vault/master?style=flat-square" alt="Code Quality">
    <a href="https://packagist.org/packages/igorsgm/ssh-vault">
        <img src="https://img.shields.io/packagist/dt/igorsgm/ssh-vault.svg?style=flat-square" alt="Total Downloads">
    </a>
</p>

<hr/>

<p align="center">
    <img src="https://raw.githubusercontent.com/igorsgm/ssh-vault/master/storage/images/ssh-vault-available-commands.png" alt="SSH Vault usage sample with available commands">
</p>

## ✨ Features
> It just parses and modifies `~/.ssh/config` file. You can continue to use tools that you like and just use this wrapper to add or remove connections from your ssh config file.

- **List Hosts:** View SSH/config file contents in various formats.
- **Add New Host:** Simplify adding new SSH connections.
- **Remove Host:** Keep your SSH config file clean by removing connections.
- **Add Connection:** Effortlessly establish new SSH connections, enhancing your SSH management workflow.

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
    <img src="https://raw.githubusercontent.com/igorsgm/ssh-vault/master/storage/images/ssh-table.png" alt="SSH Vault hosts list and table">
</p>


### 📄 Display all SSH connections in a raw format:
Unfiltered view of SSH configurations for in-depth review.
```bash
ssh-vault hosts:raw
```
<p align="center">
    <img src="https://raw.githubusercontent.com/igorsgm/ssh-vault/master/storage/images/ssh-vault-raw.png" alt="SSH Vault raw ssh config">
</p>

### 🔗 Add a new SSH connection to your config file
Interactive inputs for quick SSH connection setup.
```bash
ssh-vault hosts:add
```
<p align="center">
    <img src="https://raw.githubusercontent.com/igorsgm/ssh-vault/master/storage/images/ssh-add.png" alt="SSH Vault add new connection">
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
    <img src="https://raw.githubusercontent.com/igorsgm/ssh-vault/master/storage/images/ssh-connect.png" alt="SSH Vault remove and connect hosts">
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
