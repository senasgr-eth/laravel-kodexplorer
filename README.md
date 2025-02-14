# KodExplorer Laravel Package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/senasgr-eth/laravel-kodexplorer.svg?style=flat-square)](https://packagist.org/packages/senasgr-eth/laravel-kodexplorer)
[![Total Downloads](https://img.shields.io/packagist/dt/senasgr-eth/laravel-kodexplorer.svg?style=flat-square)](https://packagist.org/packages/senasgr-eth/laravel-kodexplorer)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

> A Laravel package that seamlessly integrates KodExplorer into your Laravel applications with enhanced security and modern features.

----

## 🌟 Features

### Modern Laravel Integration
- Seamless Laravel authentication
- Role-based access control
- User-specific storage directories
- Event system for file operations
- API for programmatic access

### Enhanced Security
- User isolation
- CSRF protection
- Permission-based access
- Secure file handling

### Developer Experience
- WebIDE capabilities
- Multiple editor themes
- Syntax highlighting
- Code completion
- Multi-language support

## 📦 Quick Start

```bash
# Install via Composer
composer require senasgr-eth/laravel-kodexplorer

# Add admin column
php artisan make:migration add_is_admin_to_users_table
php artisan migrate

# Install KodExplorer
php artisan kodexplorer:install
```

## 📚 Documentation

- [Integration Guide](INTEGRATION.md)
- [Development Guide](DEVELOPMENT.md)
- [Contributing Guide](CONTRIBUTING.md)
- [Change Log](CHANGELOG.md)

## 🔒 Security

Report security issues to s3na.s4gara@gmail.com

## 🤝 Contributing

Contributions are welcome! Please see our [Contributing Guide](CONTRIBUTING.md).

## 📄 License

This package is open-sourced software licensed under the [MIT license](LICENSE.md).

## 👥 Credits

### Original KodExplorer Features

This package builds upon the excellent foundation of KodExplorer, which includes:

#### File Management
- Complete file operations (copy, cut, paste, move, rename, etc.)
- Multi-user support with custom role groups
- Drag & drop interface
- File sharing capabilities
- Directory size calculation
- Image thumbnails
- Multi-language support
- Archive handling (zip, rar, 7z, tar, gzip)
- File preview for common formats

#### Editor Capabilities
- Syntax highlighting for 120+ languages
- Multiple themes
- Emmet integration for web development
- Code folding and auto-completion
- Vim and Emacs key bindings
- Live syntax checking
- Markdown support

### Contributors
- Original KodExplorer by [kalcaddle](https://github.com/kalcaddle/KodExplorer)
- Laravel integration by [senasgr](https://github.com/senasgr-eth)
- [All Contributors](../../contributors)

## 💖 Support

- 🐛 [Report bugs](https://github.com/senasgr-eth/laravel-kodexplorer/issues)
- 💡 [Request features](https://github.com/senasgr-eth/laravel-kodexplorer/discussions)
- 📧 Contact: s3na.s4gara@gmail.com


* Forget password
    > Login page: see the "Forget password".

* Upload with Drag & Drop
    > Browser compatibility: Chrome, Firefox and Edge

* How to make the system more secure?
    > Make sure the administrator password is more complex.  
    > Open login verification code.  
    > Set the http server to not allow list the directory;  
    > PHP Security:Set the path for open_basedir.  

# Screenshot
### file manage:
- Overview
![Overview](https://raw.githubusercontent.com/kalcaddle/static/master/images/kod/file.png)
- File list Type (icon,list,split)
![File list Type](https://raw.githubusercontent.com/kalcaddle/static/master/images/kod/file-resize.png)
- Archives create/extract/preview (zip, rar, 7z, tar, gzip, tgz)
![Archives create/extract/preview](https://raw.githubusercontent.com/kalcaddle/static/master/images/kod/file-unzip.png)
- Drag upload
![Drag upload](https://raw.githubusercontent.com/kalcaddle/static/master/images/kod/file-upload-drag.png)
- Player
![Player](https://raw.githubusercontent.com/kalcaddle/static/master/images/kod/file-player.png)
- Online Office view & Editor
![Online Office](https://raw.githubusercontent.com/kalcaddle/static/master/images/kod/file-open-pptx.png)


### Editor:
- Overview
![Overview](https://raw.githubusercontent.com/kalcaddle/static/master/images/kod/editor.png)
- Live preview
![Live preview](https://raw.githubusercontent.com/kalcaddle/static/master/images/kod/editor-preview.png)
- Search folder
![Search folder](https://raw.githubusercontent.com/kalcaddle/static/master/images/kod/editor-search.png)
- Markdown
![Markdown](https://raw.githubusercontent.com/kalcaddle/static/master/images/kod/file-markdown.png)
- Code style
![Code style](https://raw.githubusercontent.com/kalcaddle/static/master/images/kod/editor-theme.png)


### Others:
- System role
![System role](https://raw.githubusercontent.com/kalcaddle/static/master/images/kod/system-role.png)
- Colorful Theme
![Colorful Theme](https://raw.githubusercontent.com/kalcaddle/static/master/images/kod/system-theme.png)
- Custom Theme 
![Custom Theme](https://raw.githubusercontent.com/kalcaddle/static/master/images/kod/common-alpha.png)
- Language
![Language](https://raw.githubusercontent.com/kalcaddle/static/master/images/kod/language.png)




# Software requirements
- Server:
    - Windows,Linux,Mac ...
    - PHP 5.0+
    - Database: File system driver;sqlite;mysql;...
- Browser compatibility: 
    - Chrome 
    - Firefox
    - Opera
    - IE8+
> Tips: It can also run on a router, or your home NAS


# Credits
kod is made possible by the following open source projects.

* [seajs](https://github.com/seajs/seajs) 
* [jQuery](https://github.com/jquery/jquery)
* [ace](https://github.com/ajaxorg/ace)
* [zTree](https://github.com/zTree/zTree_v3) 
* [webuploader](https://github.com/fex-team/webuploader) 
* [artTemplate](http://aui.github.com/artTemplate/)
* [artDialog](https://github.com/aui/artDialog)
* [jQuery-contextMenu](http://medialize.github.com/jQuery-contextMenu/) 
* ...


# License
kodcloud is issued under GPLv3.   license.[License](http://kodcloud.com/tools/licenses/license.txt)  
Contact: warlee#kodcloud.com  
Copyright (C) 2013 kodcloud.com  

# 版权声明
kodexplorer 使用 GPL v3 协议.
