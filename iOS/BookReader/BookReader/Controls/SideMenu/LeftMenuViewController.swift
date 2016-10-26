//
//  LeftSideViewController.swift
//  BookReader
//
//  Created by Arthur on 16/9/1.
//  Copyright © 2016年 Arthur. All rights reserved.
//

import UIKit
import SnapKit;

class LeftMenuViewController: UIViewController, UITableViewDataSource, UITableViewDelegate {
//    var userButton:UIButton = UIButton();
//    var homeButton:UIButton = UIButton();
//    var messageButton:UIButton = UIButton();
//    var settingsButton:UIButton = UIButton();
    var tableView:UITableView?
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        view.backgroundColor = kColorRGB(40, g: 37, b: 34);
        
//        userButton.setImage(UIImage.init(named: "hsm_default_avatar"), forState: UIControlState.Normal);
//        homeButton.setImage(UIImage.init(named: "hsm_icon_1"), forState: UIControlState.Normal);
//        messageButton.setImage(UIImage.init(named: "hsm_icon_2"), forState: UIControlState.Normal);
//        settingsButton.setImage(UIImage.init(named: "hsm_icon_3"), forState: UIControlState.Normal);
//        
//        self.view.addSubview(userButton);
//        self.view.addSubview(homeButton);
//        self.view.addSubview(messageButton);
//        self.view.addSubview(settingsButton);
//        
//        userButton.snp.makeConstraints { (make) -> Void in
//            make.width.equalTo(21)
//            make.height.equalTo(23);
//            make.center.equalTo(self.view)
//        }
        //初始化
        self.tableView = UITableView();
        //注册Cell
        self.tableView!.registerClass(SideMenuTableViewCell.self, forCellReuseIdentifier: kDefaultCellIdentifier)

        //设置数据源和代理
        self.tableView!.delegate = self;
        self.tableView!.dataSource = self;
        self.tableView!.backgroundColor = UIColor.clearColor();
        self.tableView!.separatorColor = UIColor.blackColor();//设置分割线颜色
        self.tableView!.separatorInset = UIEdgeInsetsMake(0, 0, 0, 0);
        
        self.view.addSubview(tableView!);
        
        self.tableView!.snp_makeConstraints { [unowned self] (make) -> Void in
            make.left.right.bottom.equalTo(self.view);
            make.top.equalTo(kNavBarHeight);
        };
    }
    
    lazy var dataSource: [String] = {
        return ["a"];
    }()
    
    func tableView(tableView: UITableView, shouldHighlightRowAtIndexPath indexPath: NSIndexPath) -> Bool {
        return true;
    }

    func tableView(tableView: UITableView, numberOfRowsInSection section: Int) -> Int{
        return dataSource.count;
    }
    
    func tableView(tableView: UITableView, cellForRowAtIndexPath indexPath: NSIndexPath) -> UITableViewCell {
        let cell = tableView.dequeueReusableCellWithIdentifier(kDefaultCellIdentifier, forIndexPath: indexPath) as! SideMenuTableViewCell

        // 配置cell
        cell.textLabel!.text = "假数据 - \(dataSource[indexPath.row])"

        // 返回cell
        return cell
    }
    

    func tableView(tableView: UITableView, didSelectRowAtIndexPath indexPath: NSIndexPath) {
        //qi
        for i in 0 ..< dataSource.count{
            let cell = tableView.cellForRowAtIndexPath(NSIndexPath.init(forRow: i, inSection: indexPath.section)) as! SideMenuTableViewCell;
            
            cell.setSelected(i == indexPath.row, animated: true);
        }
    }
    
}
