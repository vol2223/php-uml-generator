php-uml-generator
=================

### It will generate a UML the place you specified in the entry point

### Required

- [plantuml](http://plantuml.sourceforge.net/)
- [emonkak/dependency-graph](https://github.com/emonkak/php-dependency-graph)

### How to use

    $ ./bin/umlGenerater Controller\\HogeController Controller\\PiyoController
    $ ls ./uml
    ControllerHogeController.dot ControllerPiyoController.dot
    $ java -jar ./bin/plantuml.jar ControllerHogeController.dot
    $ java -jar ./bin/plantuml.jar ControllerPiyoController.dot
