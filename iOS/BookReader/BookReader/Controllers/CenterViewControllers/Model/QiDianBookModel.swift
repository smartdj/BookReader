//
//  QiDianBookModel.swift
//  BookReader
//
//  Created by Arthur on 2016/10/28.
//  Copyright © 2016年 Arthur. All rights reserved.
//

import UIKit
import ObjectMapper

class QiDianBookModel: Mappable {
    var id: Int?
    var URL: String?
    var imgURL:String?
    var shortDescription:String?
    var longDescription:String?
    var name:String?
    var authorURL:String?
    var authorName:String?
    var mainCategory:String?
    var subCategory:String?
    var status:String?
    var writtenWords:String?
    
    required init?(_ map: Map) {
        
    }
    
    func mapping(map: Map) {
        id <- map["id"]
        URL <- map["URL"]
        imgURL <- map["imgURL"]
        shortDescription <- map["shortDescription"]
        longDescription <- map["longDescription"]
        name <- map["name"]
        authorURL <- map["authorURL"]
        authorName <- map["authorName"]
        mainCategory <- map["mainCategory"]
        subCategory <- map["subCategory"]
        status <- map["status"]
        writtenWords <- map["writtenWords"]
    }
}
