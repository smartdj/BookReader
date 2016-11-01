//
//  QiDianWebAPI.swift
//  BookReader
//
//  Created by Arthur on 2016/10/28.
//  Copyright © 2016年 Arthur. All rights reserved.
//

import UIKit
import ObjectMapper

//调用方式
//QiDianWebAPI.books(page:0, count: 10) { (statusCode, dataModels) -> (Void) in
//    log.debug(dataModels)
//}

class QiDianWebAPI: WebAPIBase {
    class func books(page page:UInt, count:UInt, response:((statusCode:Int, dataModels:Array<QiDianBookModel>?) -> (Void))?){
        let URL = String(format: "http://localhost/bookreader/Server/think/public/spider/api/qidianallbook/page/%u/count/%u", page, count)
        
        super.GET(URL, addtionalHeader: nil) { (statusCode, data) in
            var dataModels: Array<QiDianBookModel>?;
            
            if(statusCode == 200 && data != nil){
                let responseString = NSString(data: data!, encoding: NSUTF8StringEncoding) as String?
                dataModels = Mapper<QiDianBookModel>().mapArray(responseString)
            }
            
            if(response != nil){
                response!(statusCode: statusCode, dataModels: dataModels);
            }
        }
    }
}
