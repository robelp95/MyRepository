<?php

namespace App\Serializer\Normalizer;

use App\Entity\Producto;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ProductoNormalizer implements ContextAwareNormalizerInterface
{
    private $normalizer;
    private $urlHelper;

    public function __construct(ObjectNormalizer $normalizer, UrlHelper $urlHelper)
    {
        $this->normalizer = $normalizer;
        $this->urlHelper = $urlHelper;
    }

    /**
     * @param mixed $producto
     * @param null $format
     * @param array $context
     * @return array|\ArrayObject|bool|float|int|null|string
     */
    public function normalize($producto, string $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($producto, $format, $context);

        // Here: add, edit, or delete some data
        if(!empty($producto->getImage())){
            $data['image'] = $this->urlHelper->getAbsoluteUrl('/storage/default/' . $producto->getImage());
        }

        return $data;
    }

    public function supportsNormalization($data, string $format = null,array $context = [])
    {
        return $data instanceof Producto;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
