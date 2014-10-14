<?php

/* vim:set softtabstop=4 shiftwidth=4 expandtab: */
/**
 *
 * LICENSE: GNU General Public License, version 2 (GPLv2)
 * Copyright 2001 - 2014 Ampache.org
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License v2
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 */

namespace Beets;

/**
 * Description of handler
 *
 * @author raziel
 */
abstract class Handler
{
    /**
     * Seperator between command and arguments
     * @var string
     */
    protected $commandSeperator;

    abstract protected function start($command);

    public function setHandler(Catalog $handler, $command)
    {
        $this->handler = $handler;
        $this->handlerCommand = $command;
    }

    /**
     * Call function from the dispatcher e.g. to store the new song
     * @param mixed $data
     * @return mixed
     */
    protected function dispatch($data)
    {
        return call_user_func(array($this->handler, $this->handlerCommand), $data);
    }


    /**
     * Resolves the differences between Beets and Ampache properties
     * @param type $song
     * @return type
     */
    protected function mapFields($song)
    {
        foreach ($this->fieldMapping as $from => $to) {
            list($key, $format) = $to;
            $song[$key] = sprintf($format, $song[$from]);
        }
        $song['genre'] = explode(',', $song['genre']);

        return $song;
    }

    /**
     * Get a command to get songs with a timestamp in $tag newer than $time.
     * For example: 'ls added:2014-10-02..'
     * @param string $command
     * @param string $tag
     * @param integer $time
     * @return string
     */
    public function getTimedCommand($command, $tag, $time) {
        $commandParts = array(
            $command
        );
        if($time) {
            $commandParts[] = $tag . ':' . date('Y-m-d', $time) . '..';
        }
        return implode($this->commandSeperator, $commandParts);
    }

}
