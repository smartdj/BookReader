//
//  RightSideMenuTableViewCell.swift
//  BookReader
//
//  Created by Arthur on 2016/10/27.
//  Copyright © 2016年 Arthur. All rights reserved.
//

import UIKit

class RightSideMenuTableViewCell: UITableViewCell {
    private var _dataModel:SideMenuTableViewCellModel?
    var dataModel:SideMenuTableViewCellModel?{
        set{
            _dataModel = newValue;
            self.setSelected((_dataModel?.isSelected)!, animated: false);
            self.iconImageView?.image = newValue?.icon;
            self.textLabel?.text = _dataModel?.title
        }
        get{
            return _dataModel;
        }
    }
    
    lazy var iconImageView:UIImageView? = {
        var iconImageView = UIImageView();
        self.contentView.addSubview(iconImageView);
        iconImageView.snp_makeConstraints(closure: {[unowned self] (make) in
            make.left.equalTo(14);
            make.centerY.equalTo(self);
            make.width.height.equalTo(30);
            })
        return iconImageView;
    }()
    
    override func layoutSubviews() {
        super.layoutSubviews()
        self.textLabel?.frame.origin = CGPointMake(58, (self.textLabel?.frame.origin.y)!)
    }
    
    override func awakeFromNib() {
        super.awakeFromNib()
        // Initialization code
    }

    override func setSelected(selected: Bool, animated: Bool) {
        super.setSelected(selected, animated: animated)

        // Configure the view for the selected state
    }
    
    override func setHighlighted(highlighted: Bool, animated: Bool) {
        super.setHighlighted(highlighted, animated: animated)
    }

    override init(style: UITableViewCellStyle, reuseIdentifier: String?) {
        super.init(style: style, reuseIdentifier: reuseIdentifier);
        
        self.backgroundColor = UIColor.colorFromRBG(0x282828);
        self.selectionStyle = .Gray;
        self.textLabel?.textColor = UIColor.whiteColor()
        
        //修改选中时的高亮背景色
        let v:UIView = UIView();
        v.backgroundColor = UIColor.blackColor();
        self.selectedBackgroundView = v;
    }
    
    required init?(coder aDecoder: NSCoder) {
        super.init(coder: aDecoder);
    }
    
    
}
