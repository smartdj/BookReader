//
//  SideMenuTableViewCell.swift
//  BookReader
//
//  Created by Arthur on 2016/10/26.
//  Copyright © 2016年 Arthur. All rights reserved.
//

import UIKit

class LeftSideMenuTableViewCell: UITableViewCell {
    private var _dataModel:SideMenuTableViewCellModel?
    var dataModel:SideMenuTableViewCellModel?{
        set{
            _dataModel = newValue;
            self.setSelected((_dataModel?.isSelected)!, animated: false);
            self.iconImage?.image = newValue?.icon;
        }
        get{
            return _dataModel;
        }
    }
    
    lazy var iconImage:UIImageView? = {
        var iconImage = UIImageView();
        self.addSubview(iconImage);
        iconImage.snp_makeConstraints(closure: {[unowned self] (make) in
            make.center.equalTo(self);
            make.width.equalTo(21);
            make.height.equalTo(23);
        })
        return iconImage;
    }()
    
    var selectedIdentifior:UIView!;
    
    override func awakeFromNib() {
        super.awakeFromNib()
        // Initialization code
    }

    override func setHighlighted(highlighted: Bool, animated: Bool){
        super.setHighlighted(highlighted, animated: animated)
    }
    
    override func setSelected(selected: Bool, animated: Bool) {
        super.setSelected(selected, animated: animated)

        if let dataModel = dataModel{//init的时候会调用这个函数，这时model为nil需要校验
            selectedIdentifior.hidden = !(dataModel.isSelected);
        }
    }
    
    override init(style: UITableViewCellStyle, reuseIdentifier: String?) {
        super.init(style: style, reuseIdentifier: reuseIdentifier);
        
        self.backgroundColor = UIColor.clearColor();
        self.selectionStyle = .None;
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
