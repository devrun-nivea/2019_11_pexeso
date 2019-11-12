find vendor -name "*.gitignore" -not -path "vendor/devrun*" -delete
find vendor -name "*.git" -not -path "vendor/devrun*" -exec rm -rf {} \;
