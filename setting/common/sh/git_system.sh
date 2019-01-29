#!/bin/bash
sudo git config --system core.editor vi
sudo git config --system alias.st status
sudo git config --system alias.co checkout
sudo git config --system alias.br branch
sudo git config --system alias.up rebase
sudo git config --system alias.ci commit
sudo git config --system alias.di diff
sudo git config --system alias.diw 'diff --color-words --word-diff-regex="\w+|[^[:space:]]"'
sudo git config --system alias.glog 'log --graph --date=iso --pretty="format:%C(yellow)%h %C(cyan)%ad %C(green)%an%Creset%x09%s %C(red)%d%Creset" -16'
