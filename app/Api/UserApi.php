<?php
namespace App\Api;

use App\User;
/**
* this class is used to provides apis for user table
*/
class UserApi
{

  public function getById($id)
  {
    return User::find($id);
  }

  public function getByGoogleId($googleId)
  {
    return User::googleId($googleId)->first();
  }

  public function newUser(array $values)
  {
    $user = User::create($values);

    # create new default categories
    $categories = [
      [
        'name' => 'Sholat',
        'sub_categories' => [
          [
            'name' => 'Shubuh',
            'records' => [
              [
                'name' => 'Ya/tidak',
                'type' => 'checker'
              ],
              [
                'name' => 'Jamaah',
                'type' => 'checker'
              ]
            ]
          ],
          [
            'name' => 'Dhuhur',
            'records' => [
              [
                'name' => 'Ya/tidak',
                'type' => 'checker'
              ],
              [
                'name' => 'Jamaah',
                'type' => 'checker'
              ]
            ]
          ],
          [
            'name' => 'Ashar',
            'records' => [
              [
                'name' => 'Ya/tidak',
                'type' => 'checker'
              ],
              [
                'name' => 'Jamaah',
                'type' => 'checker'
              ]
            ]
          ],
          [
            'name' => 'Maghrib',
            'records' => [
              [
                'name' => 'Ya/tidak',
                'type' => 'checker'
              ],
              [
                'name' => 'Jamaah',
                'type' => 'checker'
              ]
            ]
          ],
          [
            'name' => 'Isyak',
            'records' => [
              [
                'name' => 'Ya/tidak',
                'type' => 'checker'
              ],
              [
                'name' => 'Jamaah',
                'type' => 'checker'
              ]
            ]
          ]
        ]
      ],
      [
        'name' => 'Puasa',
        'sub_categories'  => [
          [
            'name'  =>  'Ramadhan',
            'records' =>  [
              [
                'name'  =>  'Ya/Tidak',
                'type'  =>  'checker'
              ]
            ]
          ],
          [
            'name'  =>  'Senin Kamis',
            'records' =>  [
              [
                'name'  =>  'Ya/Tidak',
                'type'  =>  'checker'
              ]
            ]
          ],
          [
            'name'  =>  'Ayyamul Bidh',
            'records' =>  [
              [
                'name'  =>  'Ya/Tidak',
                'type'  =>  'checker'
              ]
            ]
          ]
        ]
      ],
      [
        'name' => 'Baca Quran',
        'records' => [
          [
            'name'  =>  'Lembar',
            'type'  =>  'counter'
          ]
        ]
      ]
    ];

    # simpen categories
    foreach ($categories as $key => $categoryValues) {
      $category = new \App\Category;
      $category->user_id = $user->id;
      $category->name = $categoryValues['name'];
      $category->save();

      # check if the category has sub
      if (isset($categoryValues['sub_categories'])) {
        # if so, then create the sub_categories
        foreach ($categoryValues['sub_categories'] as $key => $subCategoryValues) {
          $subCategory = new \App\SubCategory;
          $subCategory->category_id = $category->id;
          $subCategory->name = $subCategoryValues['name'];
          $subCategory->save();

          # create the records
          foreach ($subCategoryValues['records'] as $key => $recordValues) {
            $record = new \App\Record;
            $record->user_id = $user->id;
            $record->category_id = $category->id;
            $record->sub_category_id = $subCategory->id;
            $record->name = $recordValues['name'];
            $record->type = $recordValues['type'];
            $record->save();
          }
        }
      }
      else {
        # if not, just create the records without sub_categories
        foreach ($categoryValues['records'] as $key => $recordValues) {
          $record = new \App\Record;
          $record->user_id = $user->id;
          $record->category_id = $category->id;
          $record->name = $recordValues['name'];
          $record->type = $recordValues['type'];
          $record->save();
        }
      }
    }

    return $user;
  }
}
