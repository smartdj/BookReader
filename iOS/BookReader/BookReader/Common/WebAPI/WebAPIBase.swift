//
//  WebAPIBase.swift
//  BookReader
//
//  Created by Arthur on 2016/10/27.
//  Copyright © 2016年 Arthur. All rights reserved.
//

import UIKit
import Alamofire

class WebAPIBase: NSObject {
    class func GET(URL:String, addtionalHeader:[String: String]?, responseCallBack:((statusCode:Int, data:NSData?) -> (Void))?){
        Alamofire.request(.GET, URL, parameters: nil, encoding:.JSON, headers:addtionalHeader)
            .validate(statusCode: 200..<300)
            //.validate(contentType: ["application/json"])
            .responseJSON { response in
                switch response.result {
                case .Success:
                    log.debug("Validation Successful")
                case .Failure(let error):
                    log.debug(error)
                }
                
                if (responseCallBack != nil){
                    responseCallBack!(statusCode: (response.response?.statusCode)!, data: response.data)
                }
        }
    }
    
    class func POST(URL:String, jsonParameters:[String: AnyObject]?, addtionalHeader:[String: String]?, callback:(statusCode:UInt, data:NSData?) -> Void){
        
        Alamofire.request(.GET, URL, parameters: jsonParameters, encoding:.JSON, headers:addtionalHeader)
            .validate(statusCode: 200..<300)
            .validate(contentType: ["application/json"])
            .responseJSON { response in
                switch response.result {
                case .Success:
                    print("Validation Successful")
                case .Failure(let error):
                    print(error)
                }
                
                //callback(statusCode: 1, data: response.data);
        }
    }
    
}
