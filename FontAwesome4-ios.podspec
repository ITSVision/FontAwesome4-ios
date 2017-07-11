Pod::Spec.new do |s|
  s.name             = 'FontAwesome4-ios'
  s.version          = '0.1.0'
  s.summary          = 'Extended and updated FontAwesome 4.7 support for iOS'
 
  s.description      = <<-DESC
FontAwesome 4.7 support for iOS. including support for UIImageViews and NSString
                       DESC
 
  s.homepage         = 'https://github.com/ITSVision/FontAwesome4-ios'
  s.license          = { :type => 'MIT', :file => 'LICENSE' }
  s.author           = { 'Benjamin de Bos' => 'info@its-vision.nl' }
  s.source           = { :git => 'https://github.com/ITSVision/FontAwesome4-ios.git', :tag => s.version.to_s }
 
  s.ios.deployment_target = '10.0'
  s.source_files = 'FantasticView/FantasticView.swift'
  s.source_files = '*.{h,m}'
  
  s.resource_bundle = { 'FontAwesome' => 'Fonts/*.ttf' }
  s.frameworks = 'UIKit', 'CoreText'
  s.requires_arc = true  
 
end