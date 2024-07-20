**Save this code in composer.json  file in your project**


```
{
    "require": {
        "dimbadil/navi": "dev-master"
    },
    "repositories": [
        {
            "type": "vcs",
            "url":  "https://Dimbadil@bitbucket.org/Dimbadil/navi.git"
        }
    ]
}
```

**Run composer update**

***USE***
```
require 'vendor/autoload.php';
$nav = new \Navi\Navi;
```


**MENU**

*Menu with markup*
```
$nav->navigation();
```

*Menu without markup*
```
$nav->menu();
```

*Menu whith params*

1) Add params to config
```
[config]
initMenu = ru,ru-menu
```
2) Add params with menu name to widget
```
$nav->navigation(['menu' => 'menu']);
```



