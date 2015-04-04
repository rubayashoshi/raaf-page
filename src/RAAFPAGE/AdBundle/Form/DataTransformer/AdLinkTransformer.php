<?php

namespace RAAFPAGE\AdBundle\Form\DataTransformer;

use RAAFPAGE\AdBundle\Entity\AdLink;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class AdLinkTransformer implements DataTransformerInterface
{
    /**
     * Transforms an object (AdLink) to a string (link).
     *
     * @param  AdLink|null $link
     * @return string
     */
    public function transform($links)
    {
        if (null === $links) {
            return "";
        }
        foreach ($links as $link) {
            $campaignArray[] = $tag->getId();
        }
        return $link->getId();
    }

    /**
     * Transforms a string (link) to an object (AdLink).
     *
     * @param  string $link
     *
     * @return AdLink|null
     *
     * @throws TransformationFailedException if object (AdLink) is not found.
     */
    public function reverseTransform($link)
    {
        if (!$link) {
            return null;
        }

        $adLink = $this->om
            ->getRepository('RAAFPAGEAdBundle:AdLink')
            ->findOneBy(array('link' => $link))
        ;

        if (null === $adLink) {
            throw new TransformationFailedException(sprintf(
                'An AdLink with link address "%s" does not exist!',
                $link
            ));
        }

        return $adLink;
    }
}