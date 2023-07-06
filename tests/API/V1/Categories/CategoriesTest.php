<?php

namespace Tests\API\V1\Categories;

use App\repositories\Contracts\CategoryRepositoryInterface;
use Tests\TestCase;

class CategoriesTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:refresh');
    }

    public function test_ensure_we_can_create_a_new_category()
    {
        $newCategory = [
            'name' => 'Category Test',
            'slug' => 'Category-Test'
        ];

        $response = $this->call('POST','api/v1/categories',$newCategory);

        $this->assertEquals(201,$response->getStatusCode());
        $this->seeInDatabase('categories',$newCategory);
        $this->seeJsonStructure([
            'success',
            'message',
            'data' => [
                'name',
                'slug'
            ]
        ]);
    }

    public function test_ensure_we_can_delete_a_category()
    {
        $category = $this->createCategories()[0];
       
        $response = $this->call('DELETE','api/v1/categories',[
            'id' => (string) $category->getID()
        ]);

        $this->assertEquals(200,$response->getStatusCode());
        $this->seeJsonStructure([
            'success',
            'message',
            'data'
        ]);
    }

    public function test_ensure_we_can_update_a_category()
    {
        $category = $this->createCategories()[0];

        $categoryData = [
            'id' => (string)$category->getId(), 
            'name' => (string)$category->getName().' updated', 
            'slug' => (string)$category->getSlug().'-updated'
        ];

        $response = $this->call('PUT','api/v1/categories',$categoryData);

        $this->assertEquals(200,$response->getStatusCode());
        $this->seeInDatabase('categories',$categoryData);
        $this->seeJsonStructure([
            'success',
            'message',
            'data' => [
                'name',
                'slug'
            ]
        ]);
    }

    public function test_ensure_we_can_get_categories()
    {
        $this->createCategories(30);
        $pagesize = 3;
        $response = $this->call('GET','api/v1/categories',[
            'page' => 1,
            'pagesize' => $pagesize
        ]);

        $data = json_decode($response->getContent(),true);

        $this->assertCount($pagesize,$data['data']);
        $this->assertEquals(200,$response->status());
        $this->seeJsonStructure([
            'success',
            'message',
            'data'
        ]);
    }

    public function test_should_get_filterd_categories()
    {
        $pagesize = 3;
        $categorySlug = 'Category-Test';
        $response = $this->call('GET','api/v1/categories',[
            'search' => $categorySlug, 
            'page' => 1,
            'pagesize' => $pagesize
        ]);

        $data = json_decode($response->getContent(),true);

        foreach ($data['data'] as $category) {
            $this->assertEquals($category['slug'],$categorySlug);
        }
        
        $this->assertEquals(200,$response->status());
        $this->seeJsonStructure([
            'success',
            'message',
            'data'
        ]);
    }

    private function createCategories(int $count=1): array
    {
        $categoryRepository = $this->app->make(CategoryRepositoryInterface::class);

        $newCategory = [
            'name' => 'Category Test',
            'slug' => 'Category-Test'
        ];

        $categories = [];

        foreach (range(0,$count) as $item) {
           $categories[] = $categoryRepository->create($newCategory);
        }

        return $categories;
    }
}