# node.js

curl -L git.io/nodebrew | perl - setup
echo 'export PATH=$HOME/.nodebrew/current/bin:$PATH' >> ~/.profile

nodebrew ls-remote
nodebrew install-binary v*.*.*
nodebrew ls
nodebrew use v*.*.*

==== nvm ====
curl -o- https://raw.githubusercontent.com/creationix/nvm/v0.34.0/install.sh | bash
source ~/.bashrc
nvm install v10.14.2
nvm use v10.14.2

curl -o- -L https://yarnpkg.com/install.sh | bash -s -- --version 1.13.0
source ~/.bashrc
