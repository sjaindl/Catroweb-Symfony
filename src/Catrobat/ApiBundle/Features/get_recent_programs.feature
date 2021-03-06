@api
Feature: Get the most recent programs

  Background: 
    Given there are users:
      | name     | password | token      |
      | Catrobat | 12345    | cccccccccc |
      | User1    | vwxyz    | aaaaaaaaaa |
    And there are programs:
      | id | name      | description | owned by | downloads | views | upload time      | version |
      | 1  | program 1 | p1          | Catrobat | 3         | 12    | 01.01.2013 12:00 | 0.8.5   |
      | 2  | program 2 |             | Catrobat | 33        | 9     | 01.02.2013 13:00 | 0.8.5   |
      | 3  | program 3 |             | User1    | 133       | 33    | 01.01.2012 13:00 | 0.8.5   |

      
  Scenario: show recent programs
    Given I have a parameter "limit" with value "1"
    And I have a parameter "offset" with value "0"
    When I GET "/api/projects/recent.json" with these parameters
    Then I should get the json object:
      """
      {
          "completeTerm":"",
          "CatrobatInformation": {
                                   "BaseUrl":"https:\/\/localhost\/",
                                   "TotalProjects":3,
                                   "ProjectsExtension":".catrobat"
                                  },
          "CatrobatProjects":[{
                                "ProjectId": 2,
                                "ProjectName":"program 2",
                                "ProjectNameShort":"program 2",
                                "ScreenshotBig":"resources\/thumbnails\/2_large.png",
                                                      "ScreenshotSmall":"resources\/thumbnails\/2_small.png",
                                                      "Author":"Catrobat",
                                                      "Description":"",
                                                      "Uploaded": 1359723600,
                                                      "UploadedString":"",
                                                      "Version":"0.8.5",
                                                      "Views":"9",
                                                      "Downloads":"33",
                                                      "ProjectUrl":"details\/2",
                                                      "DownloadUrl":"download\/2.catrobat"
                                                    }],
          "preHeaderMessages":""
      }
      """

  Scenario: show recent programs with limit and offset
    Given I have a parameter "limit" with value "2"
    And I have a parameter "offset" with value "0"
    When I GET "/api/projects/recent.json" with these parameters
    Then I should get programs in the following order:
      | Name      |
      | program 2 |
      | program 1 |

  Scenario: show recent programs with limit and offset
    Given I have a parameter "limit" with value "2"
    And I have a parameter "offset" with value "1"
    When I GET "/api/projects/recent.json" with these parameters
    Then I should get programs in the following order:
      | Name      |
      | program 1 |
      | program 3 |

      