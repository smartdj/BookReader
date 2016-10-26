//
//  SideMenuTableViewCell.swift
//  BookReader
//
//  Created by Arthur on 2016/10/26.
//  Copyright © 2016年 Arthur. All rights reserved.
//

import UIKit

class SideMenuTableViewCell: UITableViewCell {

    var selectedIdentifior:UIView!;
    
    override func awakeFromNib() {
        super.awakeFromNib()
        // Initialization code
    }

    override func setHighlighted(highlighted: Bool, animated: Bool){
        super.setHighlighted(highlighted, animated: animated)
        
        if(highlighted){
            selectedIdentifior.backgroundColor = UIColor.redColor();
        }
    }
    
    override func setSelected(selected: Bool, animated: Bool) {
        super.setSelected(selected, animated: animated)

        selectedIdentifior.hidden = !selected;
        
        if(selected){
            selectedIdentifior.backgroundColor = UIColor.redColor();
        }
    }
    
    override init(style: UITableViewCellStyle, reuseIdentifier: String?) {
        super.init(style: style, reuseIdentifier: reuseIdentifier);
        
        self.backgroundColor = UIColor.clearColor();
        
        selectedIdentifior = UIView();
        selectedIdentifior.backgroundColor = UIColor.redColor();
        selectedIdentifior.hidden = true;
        self.addSubview(selectedIdentifior);
        selectedIdentifior.snp_makeConstraints { (make) in
            make.left.top.bottom.equalTo(self);
            make.width.equalTo(4);
        }
    }

    required init?(coder aDecoder: NSCoder) {
        super.init(coder: aDecoder);
    }
    
}
