#SigUEMA - MocaBonita

Um modulo para realizar requisições para o siguema

```sh
$ composer require jhorlima/siguema:dev-master --update-no-dev
``` 

Para integrar o modulo ao plugin, basta adicionar a integraçao ao MocaBonita Event START_WORDPRESS.

```php
<?php

//... restante do código

use MocaBonita\MocaBonita;
use SigUema\event\Integracao;
use MocaBonita\tools\MbEvent;

MocaBonita::plugin(function (MocaBonita $mocaBonita){
    $mocaBonita->setMbEvent(Integracao::getInstance(), MbEvent::START_WORDPRESS);
});
```