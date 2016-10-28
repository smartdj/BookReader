//
//  UserBookListTableViewCell.swift
//  BookReader
//
//  Created by Arthur on 2016/10/28.
//  Copyright © 2016年 Arthur. All rights reserved.
//

import UIKit

class UserBookListTableViewCell: UITableViewCell {

    var dataModel : UserBookModel?;
    
    override func awakeFromNib() {
        super.awakeFromNib()
        // Initialization code
    }

    override func setSelected(selected: Bool, animated: Bool) {
        super.setSelected(selected, animated: animated)

        // Configure the view for the selected state
    }

    override init(style: UITableViewCellStyle, reuseIdentifier: String?) {
        super.init(style: style, reuseIdentifier: reuseIdentifier);
    }
    
    required init?(coder aDecoder: NSCoder) {
        fatalError("init(coder:) has not been implemented")
    }
    
    
}
