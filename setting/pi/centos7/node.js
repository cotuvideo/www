# node.js

curl -L git.io/nodebrew | perl - setup
echo 'export PATH=$HOME/.nodebrew/current/bin:$PATH' >> ~/.bash_profile
nodebrew ls-remote
nodebrew install-binary stable
nodebrew list
nodebrew use <version>
