<?php

namespace App\Service;

use App\Entity\User;
use App\Enum\LinkExpirationType as LEType;
use App\Repository\LinkRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Exception;

class LinkService
{
    public function __construct(
        private ValidatorInterface $val,
        private LinkRepository $rep,
    ) {}

    public static function createLinkHandler(Request $request, Security $security): bool
    {
        $user = $security->getUser();

        if (is_null($user)) {
            /* return $this->json(['status' => 'Error! You need to be authenticated to create links.'], 404); */
            return false;
        }

        $content = $request->getContent();

        if (!json_validate($content)) {
            /* return $this->json(['status' => 'Error! Not a valid JSON.'], 400); */
            return false;
        }

        $link = $this->rep->fromJson($content, $user, $this->val);

        if (is_null($link)) {
            /* return $this->json(['status' => 'Error! Error during decoding.'], 400); */
            return false;
        }

        $this->rep->save($link);
        return true;
    }

    public function shortLinkHandler(User $user, string $shortUrl): ?string
    {
        $link = $this->rep->getLinkByUrl($shortUrl);

        if (is_null($link)) {
            return null;
        }

        if ($link->getOwner() !== $user) {
            return null;
        }

        $linkType = $link->getExpirationType();

        if (is_null($linkType)) {
            throw new Exception('Shouldnt happen');
        }

        if ($linkType === LEType::OneTime) {
            if ($link->getUseCount() > 0) {
                throw new Exception('Shouldnt happen');
            }

            $url = $link->getLongUrl();
            $this->rep->deleteLink($link);
            return $url;
        }

        if ($linkType === LEType::ExpireByDate) {
            if (is_null($link->getExpiryDate())) {
                throw new Exception('Shouldn happen');
            }

            $expiryDate = $link->getExpiryDate();
            $currentDate = date_create();

            if ($currentDate > $expiryDate) {
                $this->rep->deleteLink($link);
                return null;
            }
        }

        $this->rep->updateTimeAndUsage($link);

        return $link->getLongUrl();
    }
}
