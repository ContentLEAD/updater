<?php
//creds interface
/*Live (Production Enviroment Open To Public)
interface Icreds{
    const HOST = 'localhost';
    const USER = 'update_user';
    const PASS = 'rdhygu8kGmug';
    const DB = 'updater';
    const ENV = 'LIVE';
}
/*STAGING (Live testing with real sites)
interface Icreds{
    const HOST = 'localhost';
    const USER = 'update_user';
    const PASS = 'rdhygu8kGmug';
    const DB = 'updater_staging';
    const ENV = 'STAGING';
}
/*Dev (Development Work not ready for testing on Server) 
interface Icreds{
    const HOST = 'localhost';
    const USER = 'updater_dev';
    const PASS = 'rdhygu8kGmug';
    const DB = 'updater_dev';
    const ENV = 'REMOTE DEV';
}
/*Local (Local Testing) You will need to modify these to your local machine*/
interface Icreds{
    const HOST = 'localhost';
    const USER = 'root';
    const PASS = '';
    const DB = 'updater';
    const ENV = 'LOCAL DEV';
}
?>