<?php


namespace Teebb\CoreBundle\Entity\Options;


class System implements OptionInterface
{
    /**
     * @var string
     */
    private $siteName;

    /**
     * @var string|null
     */
    private $siteSlogan;

    /**
     * @var string|null
     */
    private $siteEmail;

    /**
     * @var string|null
     */
    private $icpCode;

    /**
     * @return string
     */
    public function getSiteName(): string
    {
        return $this->siteName;
    }

    /**
     * @param string $siteName
     */
    public function setSiteName(string $siteName): void
    {
        $this->siteName = $siteName;
    }

    /**
     * @return string|null
     */
    public function getSiteSlogan(): ?string
    {
        return $this->siteSlogan;
    }

    /**
     * @param string|null $siteSlogan
     */
    public function setSiteSlogan(?string $siteSlogan): void
    {
        $this->siteSlogan = $siteSlogan;
    }

    /**
     * @return string|null
     */
    public function getSiteEmail(): ?string
    {
        return $this->siteEmail;
    }

    /**
     * @param string|null $siteEmail
     */
    public function setSiteEmail(?string $siteEmail): void
    {
        $this->siteEmail = $siteEmail;
    }

    /**
     * @return string|null
     */
    public function getIcpCode(): ?string
    {
        return $this->icpCode;
    }

    /**
     * @param string|null $icpCode
     */
    public function setIcpCode(?string $icpCode): void
    {
        $this->icpCode = $icpCode;
    }
}