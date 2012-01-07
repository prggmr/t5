<?php
namespace t5;

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
 * t5 System Signals
 *
 * All signals are HEX begginning at 2554
 */
class Signals {
    // System Startup and main entry point
    const ENTRY = 0x9FA;
    // System Exit point
    const TERMINATE  = 0x9FB;
    // 404 HTTP Error
    const PAGE_NOT_FOUND = 0x9FC;
}

/**
 * t5 uses a RegexSignal and currently does nothing else other than extend it.
 * It is planned to allow for it to support RESTful signaling amongst other
 * extended features useful in URL signals.
 */
class URLSignal extends \prggmr\RegexSignal {}
