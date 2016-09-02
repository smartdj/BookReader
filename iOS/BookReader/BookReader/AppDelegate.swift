//
//  AppDelegate.swift
//  BookReader
//
//  Created by Arthur on 16/9/1.
//  Copyright © 2016年 Arthur. All rights reserved.
//

import UIKit
import RTRootNavigationController
import PKRevealController

@UIApplicationMain
class AppDelegate: UIResponder, UIApplicationDelegate, PKRevealing {

    var window: UIWindow?
    var revealController:PKRevealController?;

    func application(application: UIApplication, didFinishLaunchingWithOptions launchOptions: [NSObject: AnyObject]?) -> Bool {
        // Override point for customization after application launch.
        
        self.window = UIWindow(frame: UIScreen.mainScreen().bounds)
        self.window?.backgroundColor = UIColor.whiteColor()
        
        let centerViewController = MainViewController();
        let leftMenuViewController = LeftMenuViewController();
        let rightMenuViewController = RightMenuViewController();
        
        let mainNavigationController = RTRootNavigationController(rootViewController: centerViewController);
        
        // 初始化侧滑菜单.
        revealController = PKRevealController(frontViewController: mainNavigationController, leftViewController: leftMenuViewController, rightViewController: rightMenuViewController)
        
        // 设置侧滑菜单.
        revealController!.delegate = self;
        revealController!.animationDuration = 0.25;
        revealController!.allowsOverdraw = true;
        revealController!.disablesFrontViewInteraction = true
        revealController!.setMinimumWidth(60, maximumWidth: 60, forViewController: leftMenuViewController);
        revealController!.setMinimumWidth(260, maximumWidth: 260, forViewController: rightMenuViewController);
        
        // 指定 root view controller
        self.window?.rootViewController = revealController
        
        self.window?.makeKeyAndVisible()
        
        return true
    }
    
    //MARK: - PKRevealing
    func revealController(revealController: PKRevealController!, didChangeToState state: PKRevealControllerState) {
        
    }
    
    func revealController(revealController: PKRevealController!, willChangeToState state: PKRevealControllerState) {
        
    }
    //MARK:
    func applicationWillResignActive(application: UIApplication) {
        // Sent when the application is about to move from active to inactive state. This can occur for certain types of temporary interruptions (such as an incoming phone call or SMS message) or when the user quits the application and it begins the transition to the background state.
        // Use this method to pause ongoing tasks, disable timers, and throttle down OpenGL ES frame rates. Games should use this method to pause the game.
    }

    func applicationDidEnterBackground(application: UIApplication) {
        // Use this method to release shared resources, save user data, invalidate timers, and store enough application state information to restore your application to its current state in case it is terminated later.
        // If your application supports background execution, this method is called instead of applicationWillTerminate: when the user quits.
    }

    func applicationWillEnterForeground(application: UIApplication) {
        // Called as part of the transition from the background to the inactive state; here you can undo many of the changes made on entering the background.
    }

    func applicationDidBecomeActive(application: UIApplication) {
        // Restart any tasks that were paused (or not yet started) while the application was inactive. If the application was previously in the background, optionally refresh the user interface.
    }

    func applicationWillTerminate(application: UIApplication) {
        // Called when the application is about to terminate. Save data if appropriate. See also applicationDidEnterBackground:.
    }


}

