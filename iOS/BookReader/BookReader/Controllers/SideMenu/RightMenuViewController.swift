//
//  RightSideViewController.swift
//  BookReader
//
//  Created by Arthur on 16/9/1.
//  Copyright © 2016年 Arthur. All rights reserved.
//

import UIKit

class RightMenuViewController: UIViewController , UITableViewDataSource, UITableViewDelegate {
    
    lazy var tableView:UITableView? = {
        //初始化
        var tableView = UITableView();
        //注册Cell
        tableView.registerClass(RightSideMenuTableViewCell.self, forCellReuseIdentifier: kDefaultCellIdentifier)
        
        //设置数据源和代理
        tableView.delegate = self;
        tableView.dataSource = self;
        tableView.backgroundColor = UIColor.colorFromRBG(0x1c1c1c);
        tableView.separatorColor = UIColor.colorFromRBG(0x1d1d1d);//设置分割线颜色
        tableView.separatorInset = UIEdgeInsetsMake(0, 0, 0, 0);
        tableView.tableFooterView = UIView(frame: CGRectZero);
        return tableView;
    }()
    
    //设置单元格数据，使用懒加载
    lazy var dataSource: [SideMenuTableViewCellModel] = {
        return [SideMenuTableViewCellModel(title: "搜索", icon: UIImage.init(named: "侧边栏_右侧边栏_搜索"), isSelected:false)
            ,SideMenuTableViewCellModel(title: "书城", icon: UIImage.init(named: "侧边栏_右侧边栏_书城"), isSelected:false)
            ,SideMenuTableViewCellModel(title: "包月专区", icon: UIImage.init(named: "侧边栏_右侧边栏_包月专区"), isSelected:false)
            ,SideMenuTableViewCellModel(title: "排行榜", icon: UIImage.init(named: "侧边栏_右侧边栏_排行榜"), isSelected:false)
            ,SideMenuTableViewCellModel(title: "主题数单", icon: UIImage.init(named: "侧边栏_右侧边栏_主题书单"), isSelected:false)
            ,SideMenuTableViewCellModel(title: "分类", icon: UIImage.init(named: "侧边栏_右侧边栏_分类"), isSelected:false)
            ,SideMenuTableViewCellModel(title: "听书专区", icon: UIImage.init(named: "侧边栏_右侧边栏_听书专区"), isSelected:false)
            ,SideMenuTableViewCellModel(title: "随机看书", icon: UIImage.init(named: "侧边栏_右侧边栏_随机看书"), isSelected:false)];
    }()
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        view.backgroundColor = UIColor.whiteColor();
        
        //添加tableview
        view.addSubview(tableView!);
        
        tableView!.snp_makeConstraints { [unowned self] (make) -> Void in
            make.left.right.bottom.equalTo(self.view);
            make.top.equalTo(kStatusBarHeight);
        };
    }
    
    func tableView(tableView: UITableView, numberOfRowsInSection section: Int) -> Int{
        return dataSource.count;
    }
    
    func tableView(tableView: UITableView, heightForRowAtIndexPath indexPath: NSIndexPath) -> CGFloat {
        return 53.0
    }
    
    func tableView(tableView: UITableView, cellForRowAtIndexPath indexPath: NSIndexPath) -> UITableViewCell {
        let cell = tableView.dequeueReusableCellWithIdentifier(kDefaultCellIdentifier, forIndexPath: indexPath) as! RightSideMenuTableViewCell
        
        // 配置cell
        cell.dataModel = self.dataSource[indexPath.row];
        // 返回cell
        return cell
    }
    
    
    func tableView(tableView: UITableView, didSelectRowAtIndexPath indexPath: NSIndexPath) {
        tableView.deselectRowAtIndexPath(indexPath, animated: true)
    }
    
}
