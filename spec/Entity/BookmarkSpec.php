<?php

namespace spec\Labstag\Entity;

use Labstag\Entity\Bookmark;
use Labstag\Entity\User;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\File\File;

class BookmarkSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Bookmark::class);
    }

    public function it_should_Field_nameDefault()
    {
        $this->getName()->shouldReturn(null);
    }

    public function it_should_Field_nameNotNull()
    {
        $uuid = uniqid();
        $this->setName($uuid);
        $this->getName()->shouldReturn($uuid);
    }

    public function it_should_Field_urlDefault()
    {
        $this->getUrl()->shouldReturn(null);
    }

    public function it_should_Field_urlNotNull()
    {
        $uuid = uniqid();
        $this->setUrl($uuid);
        $this->getUrl()->shouldReturn($uuid);
    }

    public function it_should_Field_slugDefault()
    {
        $this->getSlug()->shouldReturn(null);
    }

    public function it_should_Field_slugNotNull()
    {
        $uuid = uniqid();
        $this->setSlug($uuid);
        $this->getSlug()->shouldReturn($uuid);
    }

    public function it_should_Field_refuserDefault()
    {
        $this->getRefuser()->shouldReturn(null);
    }

    public function it_should_Field_refuserNotNull()
    {
        $user = new User();
        $this->setRefuser($user);
        $this->getRefuser()->shouldReturn($user);
    }

    public function it_should_Field_fileDefault()
    {
        $this->getFile()->shouldReturn(null);
    }

    public function it_should_Field_fileNotNull()
    {
        $uuid = uniqid();
        $this->setFile($uuid);
        $this->getFile()->shouldReturn($uuid);
    }

    public function it_should_Field_enableDefault()
    {
        $this->isEnable()->shouldReturn(true);
    }

    public function it_should_Field_enableNotNull()
    {
        $boolean = false;
        $this->setEnable($boolean);
        $this->isEnable()->shouldReturn($boolean);
    }

    public function it_should_Field_contentDefault()
    {
        $this->getContent()->shouldReturn(null);
    }

    public function it_should_Field_contentNotNull()
    {
        $uuid = uniqid();
        $this->setContent($uuid);
        $this->getContent()->shouldReturn($uuid);
    }

    public function it_should_Field_imageFileDefault()
    {
        $this->getImageFile()->shouldReturn(null);
    }

    public function it_should_Field_imageFileNotNull()
    {
        $tmpfile   = tmpfile();
        $metaData  = stream_get_meta_data($tmpfile);
        $imageFile = new File($metaData['uri']);
        $this->setImageFile($imageFile);
        $this->getImageFile()->shouldReturn($imageFile);
    }
}
