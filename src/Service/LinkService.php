<?php

namespace App\Service;

/* use App\Entity\Link; */
/* use App\Repository\LinkRepository; */

class LinkService
{
    /* public function updateTimeAndUsage(Link $link, LinkRepository $rep): void */
    /* { */
    /* $link->incrementUseCount(); */
    /* $link->updateLastUseTime(); */
    /* $rep->save($link); */
    /* } */
    /**/
    /* public function updateFromJson(Link $link, string $content, LinkRepository $rep): bool */
    /* { */
    /* if (!json_validate($content)) { */
    /* return false; */
    /* } */
    /**/
    /* $obj = json_decode($content, true); */
    /* $newLongUrl = array_key_exists('longUrl', $obj) ? $obj['longUrl'] : null; */
    /* $newShortUrl = array_key_exists('shortUrl', $obj) ? $obj['shortUrl'] : null; */
    /**/
    /* if (is_null($newShortUrl) && is_null($newLongUrl)) { */
    /* return true; */
    /* } */
    /**/
    /* if (!is_null($newLongUrl)) { */
    /* $link->setLongUrl($newLongUrl); */
    /* } */
    /**/
    /* if (!is_null($newShortUrl)) { */
    /* if (!$rep->shortUrlIsUnique($newShortUrl)) { */
    /* return false; */
    /* } */
    /**/
    /* $link->setShortUrl($newShortUrl); */
    /* } */
    /**/
    /* $rep->save($link); */
    /**/
    /* return true; */
    /* } */
}
