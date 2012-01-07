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

/**
 * Creates a new subscription to the given URL signal.
 *
 * @param  closure  $function  PHP Closure that will be called for this URL.
 * @param  string  $signal  URL Signal to subscribe to.
 * @param  string  $identifier  Name of the subscriber.
 * @param  integer  $priority  Priority of this subscription.
 *
 * @return  object \prggmr\Subscription
 */
function url_subscribe($function, $signal, $identifier = null, $priority = null) {
    if (is_object($signal)) {
        if (!$signal instanceof \t5\UrlSignal) {
            throw new \InvalidArgumentException(sprintf(
                "Instance of \t5\URLSignal required %s given",
                get_class($signal)
            ));
        }
    } else {
        $signal = new \t5\URLSignal($signal);
    }
    return once($function, $signal, $identifier, $priority);
}
