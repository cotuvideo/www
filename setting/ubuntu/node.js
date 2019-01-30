# node.js

curl -L git.io/nodebrew | perl - setup
echo 'export PATH=$HOME/.nodebrew/current/bin:$PATH' >> ~/.profile

nodebrew ls-remote
nodebrew install-binary v*.*.*
nodebrew ls
nodebrew use v*.*.*

