<?php

    namespace IdnoPlugins\Text {

        class PageEdit extends \Idno\Common\Page {

            function getContent() {
                $this->gatekeeper();    // This functionality is for logged-in users only

                // Are we loading an entity?
                if (!empty($this->arguments)) {
                    $object = Entry::getByID($this->arguments[0]);
                } else {
                    $object = false;
                }

                $t = \Idno\Core\site()->template();
                $body = $t->__(array(
                    'object' => $object
                ))->draw('plugins/text/edit');

                if (empty($object)) {
                    $title = 'Write an entry';
                } else {
                    $title = 'Edit entry';
                }

                if (!empty($this->xhr)) {
                    echo $body;
                } else {
                    $t->__(array('body' => $body, 'title' => $title))->drawPage();
                }
            }

            function postContent() {
                $this->gatekeeper();

                if (!empty($this->arguments)) {
                    $object = Entry::getByID($this->arguments[0]);
                }
                if (empty($object)) $object = new Entry();

                $body = $this->getInput('body');
                if (!empty($body)) {
                    $object->body = $body;
                    $object->title = $this->getInput('title');
                    $object->setAccess('PUBLIC');
                    if ($object->save()) {
                        \Idno\Core\site()->session()->addMessage('Your entry was successfully saved.');
                        $this->forward($object->getURL());
                    } else {
                        \Idno\Core\site()->session()->addMessage('We couldn\'t save your entry.');
                    }
                } else {
                    \Idno\Core\site()->session()->addMessage('You can\'t save an empty entry.');
                }

            }

        }

    }