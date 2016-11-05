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
    
    lazy var tableView:UITableView? = {
        //初始化
        var tableView = UITableView();
        //注册Cell
        tableView.registerClass(LeftSideMenuTableViewCell.self, forCellReuseIdentifier: kDefaultCellIdentifier)
        
        //设置数据源和代理
        tableView.delegate = self;
        tableView.dataSource = self;
        tableView.backgroundColor = UIColor.clearColor();
        tableView.separatorColor = UIColor.blackColor();//设置分割线颜色
        tableView.separatorInset = UIEdgeInsetsMake(0, 0, 0, 0);
        tableView.tableFooterView = UIView(frame: CGRectZero);
        tableView.bounces = false;
        return tableView;
    }()
    
    //设置单元格数据，使用懒加载
    lazy var dataSource: [SideMenuTableViewCellModel] = {
        return [SideMenuTableViewCellModel(title: nil, icon: UIImage.init(named: "侧边栏_左侧边栏_默认头像"), isSelected:false)
        ,SideMenuTableViewCellModel(title: nil, icon: UIImage.init(named: "侧边栏_左侧边栏_主界面"), isSelected:true)
        ,SideMenuTableViewCellModel(title: nil, icon: UIImage.init(named: "侧边栏_左侧边栏_消息"), isSelected:false)
        ,SideMenuTableViewCellModel(title: nil, icon: UIImage.init(named: "侧边栏_左侧边栏_设置"), isSelected:false)];
    }()
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        view.backgroundColor = kColorRGB(40, g: 37, b: 34);
        
        //添加tableview
        view.addSubview(tableView!);
        
        tableView!.snp_makeConstraints { [unowned self] (make) -> Void in
            make.left.right.bottom.equalTo(self.view);
            make.top.equalTo(kNavBarHeight);
        };
        
        //给tableView顶部添加一个分割线
        self.view.setBorder(.topBorder, color: UIColor.blackColor(), widthOrHeight: 0.5, edgeInsets: EdgeInsetsMake(kNavBarHeight-0.5, left: 0, bottom: 0, right: 0))
    }

    func tableView(tableView: UITableView, numberOfRowsInSection section: Int) -> Int{
        return dataSource.count;
    }
    
    func tableView(tableView: UITableView, cellForRowAtIndexPath indexPath: NSIndexPath) -> UITableViewCell {
        let cell = tableView.dequeueReusableCellWithIdentifier(kDefaultCellIdentifier, forIndexPath: indexPath) as! LeftSideMenuTableViewCell

        // 配置cell
        cell.dataModel = self.dataSource[indexPath.row];
        // 返回cell
        return cell
    }
    

    func tableView(tableView: UITableView, didSelectRowAtIndexPath indexPath: NSIndexPath) {
        //
        for i in 0 ..< dataSource.count{
            let cell = tableView.cellForRowAtIndexPath(NSIndexPath.init(forRow: i, inSection: indexPath.section)) as! LeftSideMenuTableViewCell;
            cell.dataModel?.isSelected = i == indexPath.row;
            cell.setSelected(i == indexPath.row, animated: true);
        }
    }
    
}
