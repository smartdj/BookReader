//
//  SideMenuTableViewCellModel.swift
//  BookReader
//
//  Created by Arthur on 2016/10/26.
//  Copyright © 2016年 Arthur. All rights reserved.
//

import UIKit

class SideMenuTableViewCellModel: NSObject {
    var title:String?;
    var icon:UIImage?;
    var isSelected:Bool = false;
    
    init(title:String?, icon:UIImage?, isSelected:Bool) {
        super.init();
        self.title = title;
        self.icon = icon;
        self.isSelected = isSelected;
    }
}
