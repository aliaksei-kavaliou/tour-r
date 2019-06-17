<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the private 'Aws\S3\S3Client' shared autowired service.

include_once $this->targetDirs[3].'/vendor/aws/aws-sdk-php/src/AwsClientInterface.php';
include_once $this->targetDirs[3].'/vendor/aws/aws-sdk-php/src/AwsClientTrait.php';
include_once $this->targetDirs[3].'/vendor/aws/aws-sdk-php/src/AwsClient.php';
include_once $this->targetDirs[3].'/vendor/aws/aws-sdk-php/src/S3/S3ClientInterface.php';
include_once $this->targetDirs[3].'/vendor/aws/aws-sdk-php/src/Api/Parser/PayloadParserTrait.php';
include_once $this->targetDirs[3].'/vendor/aws/aws-sdk-php/src/S3/S3ClientTrait.php';
include_once $this->targetDirs[3].'/vendor/aws/aws-sdk-php/src/S3/S3Client.php';

return $this->privates['Aws\\S3\\S3Client'] = new \Aws\S3\S3Client(['endpoint' => $this->getEnv('resolve:AWS_S3_ENDPOINT'), 'version' => 'latest', 'region' => $this->getEnv('resolve:AWS_REGION'), 'credentials' => ['key' => $this->getEnv('resolve:AWS_KEY'), 'secret' => $this->getEnv('resolve:AWS_SECRET')]]);
