<?php

namespace Jocolopes\Assets;

class FakeTypes {
    public function getTypes(){
        return ['universal', 'mobile'];
    }
}

class JsonTest extends \UnitTestCase {

  public function testAssetsLoadCorrectly(){
    $json = new Json('tests/fakeassets.json', 'tests/fakeassets');
    $assets = $json->getAssets();
    $this->assertEquals(count($assets['head']), 22);
    $this->assertEquals(count($assets['body']), 11);
    $this->assertEquals($assets['head'][0], '/example/asset10.css');
    $this->assertEquals($assets['head'][1], '/example/test/asset2.css');
    $this->assertEquals($assets['body'][0], '/example/test/asset8.js');
  }

  public function testAssetsMobile(){
    $json = new Json('tests/fakeassets.json', 'tests/fakeassets', 'Jocolopes\Assets\FakeTypes');
    $assets = $json->getAssets();
    $this->assertEquals(end($assets['head']), '/mobile.css');
    $this->assertEquals(end($assets['body']), '/mobile.js');
  }

  public function testAssetsWithTag(){
    $json = new Json('tests/fakeassets.json', 'tests/fakeassets');
    $assets = $json->getAssetsWithTag();
    $this->assertEquals(count($assets['head']), 22);
    $this->assertEquals(count($assets['body']), 11);
    $this->assertEquals($assets['head'][0], '<link rel="stylesheet" type="text/css" href="/example/asset10.css"/>');
    $this->assertEquals($assets['head'][1], '<link rel="stylesheet" type="text/css" href="/example/test/asset2.css"/>');
    $this->assertEquals($assets['body'][0], '<script type="text/javascript" src="/example/test/asset8.js"></script>');
  }

}