#!/bin/bash

# Create new directory structure
mkdir -p resources/{assets,lang,plugins}
mkdir -p src/{Auth,Commands,Facades,Middleware}

# Move files to new locations
mv static/* resources/assets/
mv config/i18n/* resources/lang/
mv plugins/* resources/plugins/
mv app/core/function.php src/helpers.php

# Move core functionality to src
mv app/core/* src/
mv app/controller/* src/Controllers/

# Clean up old directories
rm -rf static config/i18n plugins app

# Remove unnecessary files
rm index.php
rm ChangeLog.md
rm LARAVEL.md
rm README_LARAVEL.md

# Create necessary Laravel package directories
mkdir -p src/{routes,views,database/migrations}

echo "Directory restructuring complete!"
