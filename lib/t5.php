<?php
/**
 *  Copyright 2010-12 Nickolas Whiting
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 *
 *
 * @author  Nickolas Whiting  <prggmr@gmail.com>
 * @package  t5
 * @copyright  Copyright (c), 2010-12 Nickolas Whiting
 */

// library version
define('T5_VERSION', '0.1.0');

// The creator
define('T5_MASTERMIND', 'Nickolas Whiting');

if (!class_exists('prggmr')) {
    if (strlen(file_get_contents('prggmr/lib/prggmr.php', true, null, 10, 1)) == 0) {
        exit('prggmr is required please check if prggmr is on your include path:
'.get_include_path().PHP_EOL.'
To install prggmr'.PHP_EOL.'
cd '.end(explode(':', get_include_path())).' && sudo git clone git://github.com/prggmrlabs/prggmr.git'.PHP_EOL
            );
    }
    require_once 'prggmr/lib/prggmr.php';
}

// start-er up
require 'signals.php';
require 'api.php';

if (!isset($events_path)) {
    // This is the path to our events
    // by default it is set to events/
    $events_path = dirname(realpath(__FILE__));   
}

/**
 * This is a nice and simple method for developing evented web software.
 * It needs only to supply a url to the init event.
 *
 * The event loop is designed to run incredibly fast to serve web pages,
 * so the loop has a shutdown of only 250ms.
 *
 * If anything it forces more attention to detail as the 250ms is a hard shutdown
 * calling exit(0) unless an error is encountered.
 */

// Load all of our event files.
$dir = new RegexIterator(
    new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($events_path)
    ), '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH
);

// Loop through and include_once each file.
foreach ($dir as $_file) {
    array_map(function($i){
        include_once $i;
    }, $_file);
}

// Lets use a global event
$event = new \prggmr\Event();

// This is the main entry point, maybe it should be called main?
// either way this signals the current path
once(function($event){
    // Always require a URL
    if (!isset($event->url)) {
        // generate a URL from request uri if nothing given 
        $event->url = $_SERVER['REQUEST_URI'];
    }
    // Parse the URL
    $url = parse_url($event->url);
    if (false === $url) {
        // If it fails throw an exception
        throw new \InvalidArgumentException(
            "An unparseable URL was provided."
        );
    }
    // Turn it into an object
    $event->url = (object) $url;
    // The path is the signal
    fire($event->url->path, null, $event);
    $return = $event->getData('return', null);
    // check the return
    if ($return === true) {
        // since we have a valid result kill it here instead of waiting
        // on the killswitch
        fire(\t5\Signals::TERMINATE, null, $event);
        exit(0);
    } elseif ($return === false) {
        // false indicates that everything stopped ... 
        // this doesn't mean we have an error
        // it could just be that the subscriber is returning the wrong result
        // or the subscriber does not want the signal to continue
        // either way a false return means to halt and that is what will
        // happen
        fire(\t5\Signals::TERMINATE, null, $event);
        exit(0);
    } else {
        // no result indicates that either the subscriber is not returning
        // properly or there weren't any subscribers either way
        // this URL is a 404
        fire(\t5\Signals::PAGE_NOT_FOUND, null, $event); 
    }
    // at this point the only thing left to do is wait for the kilswitch
}, \t5\Signals::ENTRY, 't5 Entry', 20);

// This is the main entry point and fires the entry
setTimeout(function() use (&$event){
    // Entry point
    fire(\t5\Signals::ENTRY, null, $event);
}, 0, 't5 Entry Startup');

// Shutoff everything in 250ms regardless of what is happening
setTimeout(function() use (&$event){
    // Always send the terminate signal
    fire(\t5\Signals::TERMINATE, null, $event);
    // Return a exit status of 0
    exit(0);
}, 250, 't5 Killswitch'); 

// Start the engine!
prggmr(true);
