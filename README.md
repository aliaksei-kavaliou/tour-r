Importer module.
== 

Module import data from operator(s) parse it and saves to aws s3 bucket.

Currently it supports only Dummy operator. To add operator, operator specific 
client and parser must be implemented and set in **configure.yaml**.

```yaml 
App\Service\ClientProvider:
            calls:
            - method: addClient
              arguments:
                  $operatorName: dummy
                  $client: '@App\Service\DummyClient'
    
App\Service\ParserProvider:
    calls:
    - method: addParser
      arguments:
          $operatorName: dummy
          $parser: '@App\Service\DummyParser'
```
   
Client must implement **ClientInterface**.
Parser - **ParserInterface**.

Usage
--
To start import use command app:import-tours and pass desired operators (space separated)
    
    $ bin/console app:import-tours dummy

Module usesSymfony messenger component, so messenger consumer must run. Redid is used for 
transport.
For more information about messenger configuration please see official 
documentation https://symfony.com/doc/current/components/messenger.html.

Demo
--
For test/dev purposes localstack is integrated. 
https://github.com/localstack/localstack

To start dev environment use

    $ docker-compose build && docker-compose up -d
    $ composer install
    $ bin/console app:aws-init
    
    
Test
--
    $ bin/phpunit 

